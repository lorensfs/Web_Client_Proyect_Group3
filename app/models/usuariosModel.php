<?php
require_once '../../config/database.php';

class Usuario
{
    public static function getAll(): array
    {
        global $conn;
        $sql = "SELECT u.usuarioId, u.nombre, u.correo, r.nombreRol 
                FROM usuario u 
                JOIN rol r ON r.rolId = u.rolId
                WHERE u.deleted_at IS NULL";
        return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public static function add(string $nombre, string $correo, string $password, int $rolId): int
    {
        global $conn;

        $dup = $conn->prepare("SELECT 1 FROM usuario WHERE correo = ? AND deleted_at IS NULL");
        $dup->bind_param("s", $correo);
        $dup->execute();
        if ($dup->get_result()->num_rows) {
            throw new Exception("El correo ya está registrado.");
        }

        $stmt = $conn->prepare(
            "INSERT INTO usuario (nombre, correo, password, rolId) VALUES (?, ?, ?, ?)"
        );
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bind_param("sssi", $nombre, $correo, $hash, $rolId);
        return $stmt->execute() ? 1 : 0;
    }

    public static function update(int $id, string $nombre, string $correo, ?string $password, int $rolId): int
    {
        global $conn;

        if ($password) {
            $sql = "UPDATE usuario SET nombre=?, correo=?, password=?, rolId=? WHERE usuarioId=? AND deleted_at IS NULL";
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssii", $nombre, $correo, $hash, $rolId, $id);
        } else {
            $sql = "UPDATE usuario SET nombre=?, correo=?, rolId=? WHERE usuarioId=? AND deleted_at IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssii", $nombre, $correo, $rolId, $id);
        }
        return $stmt->execute() ? 1 : 0;
    }

    public static function delete(int $id): int
    {
        global $conn;
        $sql = "UPDATE usuario SET deleted_at = NOW() WHERE usuarioId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute() ? 1 : 0;
    }
}

class Rol
{
    public static function getAll(): array
    {
        global $conn;
        $sql = "SELECT * FROM rol WHERE deleted_at IS NULL";
        return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public static function add(string $nombre, string $descripcion): int
    {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO rol (nombreRol, descripcion) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $descripcion);
        return $stmt->execute() ? 1 : 0;
    }

    public static function update(int $id, string $nombre, string $descripcion): int
    {
        global $conn;
        $stmt = $conn->prepare(
            "UPDATE rol SET nombreRol = ?, descripcion = ? WHERE rolId = ? AND deleted_at IS NULL"
        );
        $stmt->bind_param("ssi", $nombre, $descripcion, $id);
        return $stmt->execute() ? 1 : 0;
    }

    public static function delete(int $id): int
    {
        global $conn;
        $stmt = $conn->prepare("UPDATE rol SET deleted_at = NOW() WHERE rolId = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute() ? 1 : 0;
    }
}
