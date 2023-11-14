# Web2-TPE-API-REST

Distribución De Responsabilidades:
Miembro A -> Elias López encargado de Listado ordenado - PUT - Ordenado por cualquier campo (opcional) - Paginado.
Miembro B -> Tomás Almaraz Obtener un elemento por ID - POST - Filtrado (opcional) - Autenticación Token.
Aclaración : Igualmente en todo el trabajo nos fuimos ayudando mutuamente en cada responsabilidad, ya que también lo fuimos haciendo en las clases de consulta.

#Endpoints

[GET] .../api/jugadores/ID obtiene un jugador especificado por su ID

[GET] .../api/jugadores/ listará todos los jugadores que hay en nuestra base de datos (Por default vienen ordenados por nombre ascendentemente)
ORDENAMIENTO: Se deberá indicar,
?campo= el cual deberá indicar una columna por la cual ordenar, esta debe existir en la base de datos, como por ejemplo "edad", sino se avisara.
&orden= que debe ser "asc" o "desc",sino se especifica se considera que es "asc" y si el valor ingresado es otra cosa directamente damos un aviso.

FILTRADO: Para filtrar nosotros elegimos que se pueda filtrar por nacionalidad, para filtrar por nacionalidad hay que indicar,
?nacionalidad=Argentina por ejemplo y listara todos los jugadores de Argentina, sino existe ningun jugador de ese país, se devolvera un arreglo vacío, ya que ninguno cumple con esa condición.

PAGINADO: Para paginar se deberá indicar,
?pagina= la página que se solicita ver, si indicas una página mayor a la cantidad de jugadores serás avisado.
&limite= la cantidad de jugadores por página que se desea ver, si indicas un límite mayor a la cantidad de jugadores, serás avisado. Un ejemplo que aplica ordenado, filtrado y paginado sería,

.../api/jugadores?campo=edad&orden=ASC&nacionalidad=Argentina&pagina=1&limite=4

Adjuntamos al repositorio link de diagrama guía para el testeo de los GET request.

[DELETE] .../api/jugadores/ID eliminará el jugador que tenga el id específicado, si no existe te avisa

[PUT] .../api/jugadores/ID para editar la información del jugador con el id especifico, se deberá tener el token, que se obtiene con el username webadmin y la password admin en la basic auth y en este
se deberá mandar los siguientes datos en formato json, este es un ejemplo:

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
[POST] .../api/jugadores/   Para agregar un jugador,  se deberá tener el token, que se obtiene con el username webadmin y la password admin en la basic auth y en este se deberá mandar los siguientes datos en formato json, este es un ejemplo

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