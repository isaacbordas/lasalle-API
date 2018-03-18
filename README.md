Entregable MPWAR-API
========================
Enunciado:

En este ejercicio pondrás en práctica todos los conocimientos adquiridos durante el transcurso de la asignatura. Deberás aplicar también todas las herramientas de las que dispongas para realizar un desarrollo eficiente del software: patrones de diseño, refactorings, arquitectura hexagonal, etc. 

El objetivo de este ejercicio es crear una API (mayoritariamente) para almacenar películas y sus actores. 

Las dos entidades principales serán Film y Actor. 

Un Film tendrá las propiedades name y description y se relacionará con un Actor, que tendrá la propiedad name. 

La API HTTP debe permitir, para los Film: todas las operaciones CRUD. La API HTTP para los Actor debe pemitir, como mínimo, las operaciones CRD. Además, se ofrecerán dos páginas de interfaz de usuario, una para la lectura de las propiedades de un Film en concreto y otra para la lectura de las propiedades de un Actor en concreto. Estas páginas de interfaz de usuario deben estar convenientemente internacionalizadas en inglés y español. Los textos dinámicos (nombre, descripción, etc.) no deben estar internacionalizados, sólo las etiquetas. Por ejemplo: Nombre/Name: National Treasure. 

Además, se deberá permitir realizar todas las operaciones CRUD de Film por consola mediante operaciones por la consola (bin/console) de Symfony. 

Finalmente, como la página recibirá muchas peticiones, necesitarás implementar una caché, de momento usando el Sistema de Ficheros. Cada vez que se haga una petición de una película, la caché comprobará si la tiene cacheada o no. Si la tiene cacheada, devolverá los datos de la caché. Si no la tiene cacheada pedirá los datos a la base de datos y la cacheará. Si la película es actualizada, se invalidará la caché. Las funcionalidades CRUD contra la base de datos deben ser independientes de si existe caché o no. Si existe caché se decorarán las operaciones CRUD. Puedes utilizar como referencia el enlace: Caching, pero es posible que te falte información. 

Deberás aplicar arquitectura hexagonal y buenas prácticas de diseño en la medida de lo posible. Es obligatorio usar eventos, te pueden ser muy útiles para algunas funcionalidades de la caché. 

USO
========================

## Web interface

###### Mostrar actor determinado
{ServerAddress}/en/showActor/{id}/
###### Mostrar film determinado
{ServerAddress}/en/showFilm/{id}/
## API interface (Operaciones CRUD)
###### Mostrar actor determinado
POST {ServerAddress}/api/actorById/

JSON: {"id":"1"}
###### Mostrar film determinado
POST {ServerAddress}/api/filmById/

JSON: {"id":"1"}
###### Listado de actores
GET {ServerAddress}/api/actor/
###### Listado de films
GET {ServerAddress}/api/film/
###### Crear actor
POST {ServerAddress}/api/create/actor/

JSON: {"name":"Actor's Name"}
###### Crear film
POST {ServerAddress}/api/create/film/

JSON: {"name":"Film's name", "description":"Film's description", "actorId":"1"}
###### Update actor
PUT {ServerAddress}/api/update/actor/1/

JSON: {"name":"Actor's Name"}
###### Update film
PUT {ServerAddress}/api/update/film/1/

JSON: {"name":"Film's name", "description":"Film's description", "actorId":"1"}
###### Eliminar actor determinado
DELETE {ServerAddress}/api/delete/actor/

JSON: {"id":"1"}
###### Eliminar film determinado
DELETE {ServerAddress}/api/delete/film/

JSON: {"id":"1"}
## CLI interface (Operaciones CRUD)
###### Mostrar actor determinado
php bin/console app:list-actor 1
###### Mostrar film determinado
php bin/console app:list-film 1
###### Listado de actores
php bin/console app:list-actor
###### Listado de films
php bin/console app:list-film
###### Crear actor
php bin/console app:create-actor "Actor's Name"
###### Crear film
php bin/console app:create-film "Film CLI 1" "Description CLI 1" 1
###### Update actor
php bin/console app:update-actor 1 "Actor's Name"
###### Update film
php bin/console app:update-film 1 "Film CLI 1" "Description CLI 1" 1
###### Eliminar actor determinado
php bin/console app:delete-actor 1
###### Eliminar film determinado
php bin/console app:delete-film 1