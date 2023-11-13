# Web2-TPE-API-REST

Distribucion De Responsabilidades
Miembro A -> Elias López encargado de Listado ordenado - PUT - Ordenado por cualquier campo (opcional) - Paginado
Miembro B -> Tomás Almaraz Obtener un elemento por ID - POST - Filtrado (opcional) - Autenticacion Token
Aclaracion : Igualmente en todo el trabajo nos fuimos ayudando mutuamente en cada responsabilidad, ya que tambien lo fuimos haciendo en las clases de consulta

#Endpoints

[GET] .../api/jugadores/:ID obtiene un jugador especificado por su ID
[GET] .../api/jugadores/ listará todos los jugadores que hay en nuestra base de datos (Por default vienen ordenados por nombre ascendentemente)
ORDENAMIENTO: para ordenar se debera indicar 
?campo= el cual debera indicar una columna por la cual ordenar, esta debe existir en la base de datos, sino se avisara.
&orden= que debe ser "asc" o "desc",sino se especifica se considera que es "asc" y si el valor ingresado es otra cosa directamente damos un aviso.

FILTRADO: para filtrar nosotros elejimos que se pueda filtrar por nacionalidad, para filtrar por nacionalidad hay que indicar
?nacionalidad=Argentina por ejemplo y listara todos los jugadores de Argentina, sino existe ningun jugador de ese pais, se devolvera un arreglo vacio, ya que ninguno cumple con esa condicion.

PAGINADO: para paginar se debera indicar
?pagina= la pagina que se solicita ver, si indicas una pagina mayor a la cantidad de jugadores seras avisado
&limite= la cantidad de jugadores por pagina que se desea ver, si indicas un limite mayor a la cantidad de jugadores, serás avisado. Un ejemplo que aplica ordenado filtrado y paginado seria

.../api/jugadores?campo=edad&orden=ASC&nacionalidad=Argentina&pagina=1&limite=4

[DELETE] .../api/jugadores/:ID eliminara el jugador que tenga el id especificado, sino existe te avisa

[PUT] .../api/jugadores/:ID para editar la informacion del jugador con el id especifico, se debera tener el token, que se obtiene con el username webadmin y la password admin en la basic auth y en este
se debera mandar los siguientes datos en el siguiente formato

```json
    {
        "nombre": "Walter Bou",
        "edad": 30,
        "nacionalidad": "Argentina",
        "posicion": "Delantero",
        "pie_habil": "Diestro",
        "id_club": 3
    }
```
[POST] .../api/jugadores/ para agregar un jugador,  se debera tener el token, que se obtiene con el username webadmin y la password admin en la basic auth y en este se debera mandar los siguientes datos en el siguiente formato

```json
{
        "nombre": "Walter Bou",
        "edad": 30,
        "nacionalidad": "Argentina",
        "posicion": "Delantero",
        "pie_habil": "Diestro",
        "id_club": 3
    }
```
(El id del jugador se crea automaticamente).
