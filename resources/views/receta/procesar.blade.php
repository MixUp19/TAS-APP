<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title>Procesar Receta</title>
    <style>
        body{font-family: Arial, sans-serif; padding:20px}
        .grid{display:grid; grid-template-columns: 1fr 1fr; gap:20px}
        table{width:100%; border-collapse: collapse}
        th,td{border:1px solid #ddd; padding:8px}
        th{background:#f4f4f4}
        .btn{padding:8px 12px; background:#2b6cb0; color:#fff; border:none; cursor:pointer}
        .btn.danger{background:#c53030}
    </style>
</head>
<body>
    <h1>Procesar receta</h1>
    <div class="grid">
        <div>
            <h3>Buscar medicamento</h3>
            <input id="search" placeholder="Escribe nombre..." style="width:100%; padding:8px" />
            <ul id="results"></ul>
        </div>
        <div>
            <h3>Lineas seleccionadas</h3>
            <table>
                <thead>
                    <tr><th>Medicamento</th><th>Cantidad</th><th>Precio unit.</th><th>Subtotal</th><th></th></tr>
                </thead>
                <tbody id="lines-body">
                </tbody>
                <tfoot>
                    <tr><td colspan="3" style="text-align:right"><strong>Total:</strong></td><td id="total">0.00</td><td></td></tr>
                </tfoot>
            </table>
            <div style="margin-top:12px">
                <button id="send" class="btn">Enviar pedido</button>
            </div>
            <div id="messages" style="margin-top:12px;color:green"></div>
        </div>
    </div>

    <script>
        // Lista embebida de medicamentos desde el servidor
        const MEDS = @json($medicamentos);
        let lines = [];

        const searchInput = document.getElementById('search');
        const resultsEl = document.getElementById('results');
        const linesBody = document.getElementById('lines-body');
        const totalEl = document.getElementById('total');
        const messages = document.getElementById('messages');

        function renderResults(list){
            resultsEl.innerHTML = '';
            list.forEach(m => {
                const li = document.createElement('li');
                li.textContent = m.nombre + ' — $' + m.precio.toFixed(2);
                li.style.cursor = 'pointer';
                li.onclick = () => addLine(m);
                resultsEl.appendChild(li);
            });
        }

        function addLine(med){
            const existing = lines.find(l => l.medicamento_id === med.id);
            if(existing){
                existing.cantidad += 1;
            } else {
                lines.push({medicamento_id: med.id, nombre: med.nombre, cantidad: 1, precio: med.precio});
            }
            renderLines();
        }

        function renderLines(){
            linesBody.innerHTML = '';
            let total = 0;
            lines.forEach((l, idx) => {
                const tr = document.createElement('tr');
                const subtotal = l.cantidad * l.precio;
                total += subtotal;
                tr.innerHTML = `
                    <td>${l.nombre}</td>
                    <td><input type="number" min="1" value="${l.cantidad}" data-idx="${idx}" class="qty" style="width:70px"/></td>
                    <td>$${l.precio.toFixed(2)}</td>
                    <td>$${subtotal.toFixed(2)}</td>
                    <td><button class="btn danger" data-idx="${idx}">Eliminar</button></td>
                `;
                linesBody.appendChild(tr);
            });
            totalEl.textContent = total.toFixed(2);

            // attach listeners
            document.querySelectorAll('.qty').forEach(inp => {
                inp.addEventListener('change', (e) => {
                    const i = parseInt(e.target.dataset.idx);
                    const v = parseInt(e.target.value) || 1;
                    lines[i].cantidad = v;
                    renderLines();
                });
            });
            document.querySelectorAll('.btn.danger').forEach(b => {
                b.addEventListener('click', (e) => {
                    const i = parseInt(e.target.dataset.idx);
                    lines.splice(i,1);
                    renderLines();
                });
            });
        }

        searchInput.addEventListener('input', (e) => {
            const q = e.target.value.toLowerCase().trim();
            if(!q){
                renderResults(MEDS.slice(0,10));
                return;
            }
            const filtered = MEDS.filter(m => m.nombre.toLowerCase().includes(q));
            renderResults(filtered);
        });

        // inicial
        renderResults(MEDS.slice(0,10));

        document.getElementById('send').addEventListener('click', async () => {
            if(lines.length === 0) { messages.style.color='red'; messages.textContent = 'No hay líneas para enviar.'; return; }
            const payload = {
                lineas: lines.map(l => ({medicamento_id: l.medicamento_id, cantidad: l.cantidad, precio: l.precio})),
                total: parseFloat(totalEl.textContent)
            };

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try{
                const res = await fetch('/receta/pedir', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if(res.ok && data.success){
                    messages.style.color='green'; messages.textContent = data.message || 'Pedido enviado.';
                    // limpiar
                    lines = [];
                    renderLines();
                } else {
                    messages.style.color='red';
                    messages.textContent = data.message || 'Error: ' + JSON.stringify(data.errors || data);
                }
            } catch(err){
                messages.style.color='red'; messages.textContent = 'Error en conexión';
            }
        });
    </script>
</body>
</html>
