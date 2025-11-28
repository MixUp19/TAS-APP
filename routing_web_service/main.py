from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from typing import List, Tuple
from math import radians, cos, sin, asin, sqrt
from ortools.constraint_solver import routing_enums_pb2
from ortools.constraint_solver import pywrapcp

app = FastAPI(title="API de Optimización de Rutas Farmacéuticas")

class Farmacia(BaseModel):
    id: int
    nombre: str
    latitud: float
    longitud: float

class RutaResponse(BaseModel):
    ruta_optima: List[Farmacia]
    distancia_total_metros: int
    mensaje: str


def haversine_distance(lat1, lon1, lat2, lon2) -> int:
    """
    Calcula la distancia del círculo máximo entre dos puntos 
    en la tierra (especificada en grados decimales).
    Devuelve la distancia en METROS (entero).
    """
    lon1, lat1, lon2, lat2 = map(radians, [lon1, lat1, lon2, lat2])

    dlon = lon2 - lon1 
    dlat = lat2 - lat1 
    a = sin(dlat/2)**2 + cos(lat1) * cos(lat2) * sin(dlon/2)**2
    c = 2 * asin(sqrt(a)) 
    r = 6371000 # Radio de la Tierra en metros
    return int(c * r)

def compute_distance_matrix(locations: List[Farmacia]) -> List[List[int]]:
    matrix = []
    for from_node in locations:
        row = []
        for to_node in locations:
            if from_node.id == to_node.id:
                row.append(0)
            else:
                dist = haversine_distance(
                    from_node.latitud, from_node.longitud,
                    to_node.latitud, to_node.longitud
                )
                row.append(dist)
        matrix.append(row)
    return matrix

def solve_tsp_ortools(distance_matrix: List[List[int]], start_index: int = 0):
    manager = pywrapcp.RoutingIndexManager(len(distance_matrix), 1, start_index)
    routing = pywrapcp.RoutingModel(manager)

    def distance_callback(from_index, to_index):
        from_node = manager.IndexToNode(from_index)
        to_node = manager.IndexToNode(to_index)
        from_node = manager.IndexToNode(from_index)
        to_node = manager.IndexToNode(to_index)
        return distance_matrix[from_node][to_node]

    transit_callback_index = routing.RegisterTransitCallback(distance_callback)

    routing.SetArcCostEvaluatorOfAllVehicles(transit_callback_index)

    search_parameters = pywrapcp.DefaultRoutingSearchParameters()
    search_parameters.first_solution_strategy = (
        routing_enums_pb2.FirstSolutionStrategy.PATH_CHEAPEST_ARC
    )

    solution = routing.SolveWithParameters(search_parameters)

    if solution:
        index = routing.Start(0)
        route_indices = []
        route_distance = 0
        
        while not routing.IsEnd(index):
            route_indices.append(manager.IndexToNode(index))
            previous_index = index
            index = solution.Value(routing.NextVar(index))
            route_distance += routing.GetArcCostForVehicle(previous_index, index, 0)
        
        route_indices.append(manager.IndexToNode(index))
        
        return route_indices, route_distance
    else:
        return None, 0


@app.post("/optimizar-ruta", response_model=RutaResponse)
async def optimizar_ruta(farmacias: List[Farmacia]):
    if len(farmacias) < 2:
        raise HTTPException(status_code=400, detail="Se necesitan al menos 2 farmacias para calcular una ruta.")

    dist_matrix = compute_distance_matrix(farmacias)

    orden_indices, distancia_total = solve_tsp_ortools(dist_matrix, start_index=0)

    if not orden_indices:
        raise HTTPException(status_code=500, detail="No se pudo encontrar una solución óptima.")

    farmacias_ordenadas = [farmacias[i] for i in orden_indices]

    return RutaResponse(
        ruta_optima=farmacias_ordenadas,
        distancia_total_metros=distancia_total,
        mensaje="Ruta calculada exitosamente usando TSP y Haversine."
    )