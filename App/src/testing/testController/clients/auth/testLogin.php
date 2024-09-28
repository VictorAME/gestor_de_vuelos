<?php
use PHPUnit\Framework\TestCase;

final class testingLogin extends TestCase {
    private $login;

    // Método setUp sin parámetros
    public function setUp(): void {
        $email = "victor@gmail.com";
        $password = "123";
        // $this->login = new LoginRol($email, $password); // Instanciamos la clase LoginRol aquí
    }

    // Corregir el nombre del método de prueba
    public function testCanLoginForRole(): void {
        // Lógica de prueba:
        $this->assertNotNull($this->login); // Ejemplo de aserción para verificar que el objeto no sea nulo

        // Aquí podrías agregar más pruebas relacionadas con el login y el rol:
        // $this->assertEquals("expectedValue", $this->login->someMethod());
    }

    public function testCantLoginForRole(): void {
        // $this->expectException(PDOException);
    }
}
