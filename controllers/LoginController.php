<?php

namespace Controllers;
use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router){
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();
            if (empty($alertas)){
                //comprobar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);
                if ($usuario){
                    //verificar password
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)){
                        //Autenticar el usuario
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre. " ". $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //redireccionamiento
                        if ($usuario->admin === "1"){
                            $_SESSION['admin'] === $usuario->admin ?? null ; 
                            header('Location: /admin');
                        }else{
                            header('Location: /cita');
                        }
                        debuguear($_SESSION);
                    };
                }else{
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    public static function logout(){
        echo "Desde el logout";
    }

    public static function olvide(Router $router){
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if (empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario && $usuario->confirmado === "1"){
                    //Generar un token de un solo uso
                    $usuario->crearToken();
                    $usuario->guardar();
                    //Enciar email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarInstrucciones();

                    //Alerta
                    Usuario::setAlerta('exito', 'revisa tu email'); 
                    
                }else{
                    Usuario::setAlerta('error', 'El usu aruio no existe o no esta confirmado');
                    
                }
                
            }
            
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router ){
        $alertas = [];
        $error = false;
        $token = s($_GET['token']);
        //Buscar usuario por su token
        $usuario = Usuario::where('token', $token);
        if (empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
            $error = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            // Leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();
            if (empty($alertas)){
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();
                if ($resultado){
                    header('Location: /');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'erorr' => $error
        ]);
    }

    public static function crear(Router $router){
        $usuaruo = new Usuario;

        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuaruo->sincronizar($_POST);
            $alertas = $usuaruo->validarNuevaCuenta();

            if (empty($alertas)) {
                //verificar que el usuario no este registrado
                $resultado = $usuaruo->existeUsuario();
                
                
                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                }else{
                    //hashear password
                    $usuaruo->hashPassword();

                    //generar un token unico
                    $usuaruo->crearToken();

                    //Enviar el Email
                    $email = new Email($usuaruo->email, $usuaruo->nombre, $usuaruo->token);

                    $email->enviarConfirmacion();

                    //crear usuaruio
                    $resultado = $usuaruo->guardar();
                    if ($resultado){
                        header('Location: /mensaje');
                    }

                }
            }
        }
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuaruo,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }
    public static function confirmar(Router $router){
        $alertas = [];
        $token = s($_GET['token']);
        
        $usuario = Usuario::where('token', $token);
        
        if (empty($usuario)) {
            //mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no valido');
        }else{
            //modificar a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Usuario confirmado correctamente');
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}