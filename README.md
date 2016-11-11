Backend de la prueba para Rappi
===================

 El backend está desarrollado bajo el uso del framework Laravel, el cual está escrito en el lenguaje PHP. Laravel trabaja según el patrón de arquitectura de software MVC (Modelo-Vista-Controlador). Debido a que dicho backend trabaja como una API y no necesita almacenar información, no dispone del componente Modelo y posee una única Vista. El Controlador 'TestCaseController' se encarga de recibir y procesar los datos del frontend, para devolverle una respuesta. Es responsable de: armar la matriz NxNxN para cada testcase, ejecutar el conjunto de operaciones de cada testcase sobre su respectiva matriz, bien sean de tipo UPDATE o QUERY y devolver los resultados en formato JSON. El controlador ejecuta una serie de pruebas sobre los datos recibidos, para, en caso de recibir datos erróneos, poder responder con un estado 400 (bad request) en vez de 500 (Internal Server Error).

 1. Instalación
-------------
> Para poder descargar las dependencias del backend, debe tener instalado en su computador la herramienta [composer](https://getcomposer.org/).

 Clone este repositorio y diríjase a la carpeta creada, luego ejecute `composer update`.

 2. Ejecución
-------------

Dentro de la carpeta clonada, ejecute `php artisan serve`. Luego, en su explorador diríjase a la dirección [http://localhost:8000/](http://localhost:8000/). Como se dijo anteriormente, los archivos minificados del frontend se agregaron a la carpeta `public` de Laravel, por lo que se encontrará con el frontend. En dicha dirección podrá encontrar las instrucciones de uso.

3. Pruebas unitarias
-------------

Para realizar las pruebas unitarias, ejecute `./vendor/bin/phpunit`.
