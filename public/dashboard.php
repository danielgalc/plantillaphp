<?php

session_start() ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis noticias</title>
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.4/dist/flowbite.min.css" />
    <script>
        function cambiar(el, id) {
            el.preventDefault();
            const oculto = document.getElementById('hidden');
            oculto.setAttribute('value', id);
        }

        function borrar(el, id) {
            el.preventDefault();
            const oculto = document.getElementById('oculto');
            oculto.setAttribute('value', id);
        }
    </script>
</head>

<body>
    <div>
        <?php

        require '../vendor/autoload.php';
        require '../src/_menu.php';
        require '../src/_alerts.php';

        $id = \App\Tablas\Usuario::logueado()->id;

        $pdo = $pdo ?? conectar();
        $sent = $pdo->prepare('SELECT * FROM noticias WHERE noticia_usuario = :id');
        $sent->execute([':id' => $id]);
        ?>

        <div class="w-full flex justify-center items-center">
            <button type="button" class="my-10 bg-pink-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full" type="button" data-modal-toggle="insertar">Insertar nueva noticia</button>
        </div>

        <div class="w-full flex justify-center items-center">
            <table>
                <thead class="border-b">
                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">Titular</th>
                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">Likes</th>
                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-center">Acciones</th>
                </thead>
                <tbody>
                    <?php foreach ($sent as $fila) : ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $fila['titular'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $fila['likes'] ?> ♡</td>
                            <td class="flex text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                <form action="borrar.php">
                                    <input type="hidden" name="id" value="<?= $fila['id'] ?>">
                                    <button type="button" onclick="borrar(event, <?= $fila['id'] ?>)" class="bg-red-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full" type="button" data-modal-toggle="borrar">Borrar</button>
                                </form>
                                <form action="modificar.php" method="POST" class="inline">
                                    <input type="hidden" name="id" value="<?= $fila['id'] ?>">
                                    <button type="button" onclick="cambiar(event, <?= $fila['id'] ?>)" class="bg-green-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full" type="button" data-modal-toggle="modificar">Modificar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>


        <!-- MODAL BORRAR -->

        <div id="borrar" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
            <div class="relative w-full h-full max-w-md md:h-auto">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="borrar">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-6 text-center">
                        <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">¿Está seguro de que desea eliminar esta noticia?</h3>
                        <form action="/borrar.php" method="post">
                            <input type="hidden" id="oculto" name="id">
                            <button data-modal-toggle="borrar" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                Sí, estoy seguro
                            </button>
                        </form>
                        <button data-modal-toggle="borrar" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, quiero volver</button>
                    </div>
                </div>
            </div>
        </div>



        <!-- MODAL MODIFICAR -->

        <!-- Main modal -->
        <div id="modificar" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
            <div class="relative w-full h-full max-w-md md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="modificar">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Cerrar</span>
                    </button>
                    <div class="px-6 py-6 lg:px-8">
                        <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Modificar noticia</h3>
                        <form class="space-y-6" action="/modificar.php" method="post">
                            <div class="mb-2">
                                <label for="titular" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Titular</label>
                                <input type="text" name="titular" id="titular" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Ejemplo de Titular" required>
                                <input type="hidden" id="hidden" name="id">
                            </div>
                            <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Modificar titular</button></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- MODAL INSERTAR -->

        <!-- Main modal -->
        <div id="insertar" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
            <div class="relative w-full h-full max-w-md md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="insertar">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Cerrar</span>
                    </button>
                    <div class="px-6 py-6 lg:px-8">
                        <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Insertar noticia</h3>
                        <form class="space-y-6" action="/insertar.php" method="post">
                            <div class="mb-2">
                                <label for="titular" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Titular</label>
                                <input name="titular" id="titular" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Ejemplo de Titular" required>
                                <input type="hidden" id="usuario" name="usuario" value="<?= \App\Tablas\Usuario::logueado()->id ?>">
                                <p><?php var_dump(\App\Tablas\Usuario::logueado()->id)?></p>
                            </div>
                            <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Insertar titular</button></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
    </div>
    <script src="https://unpkg.com/flowbite@1.5.4/dist/flowbite.js"></script>
</body>

</html>