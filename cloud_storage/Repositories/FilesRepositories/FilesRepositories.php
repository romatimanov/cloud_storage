<?php

namespace App\Repositories\FilesRepositories;

use App\Core\Db\DbInterface;
use App\Core\Response\Response;
use Exception;

interface FilesInterface
{
    public function list(): array;
    public function getFiles($id): array;
    public function addFilesRep(string $fileName, ?int $derectId = null, int $subDerectId = null, ?int $isShared = null): ?Response;
    public function renameFiles(int $id, string $fileNewName): ?Response;
    public function updateDirectFiles(int $id, int $newDerect): ?Response;
    public function updateSubDirectFiles(int $id, int $newSubDerect): ?Response;
    public function removeFilesRep(int $id): ?Response;
    public function getDerectories(int $id): array;
    public function addDerectories(string $directoriesName): ?Response;
    public function addSubdirectory(string $subdirectoryName, int $parent_id): ?Response;
    public function renameDerectories(int $id, string $DerectNewName): ?Response;
    public function removeDerectories(int $id): ?Response;
    public function downloadFiles(int $id): ?Response;
    public function fileAccess(int $fileId, int $userId): ?Response;
    public function removeFileAccess(int $fileId, int $userId): ?Response;
    public function getUserFile(int $fileId): array;
}

class FilesRepositories implements FilesInterface
{
    private $db;

    public function __construct(DbInterface $db)
    {
        $this->db = $db;
    }

    private function checkSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $token = $_SESSION['token'] ?? null;

        if (!$token) {
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }
    }

    public function list(): array
    {
        $this->checkSession();
        try {
            $sql = "SELECT `file_name`, `derectories_id`, `id` FROM `files`";
            return $this->db->findAll($sql);
        } catch (Exception $e) {
            return new Response(500, ['error' => "Ошибка при отображении файлов: " . $e->getMessage()]);
        }
    }

    public function getFiles($id): array
    {
        $this->checkSession();
        try {
            $sql = "SELECT `file_name`, `derectories_id` FROM `files` WHERE id = :id";
            $params = [':id' => $id];
            return $this->db->findAll($sql, $params);
        } catch (Exception $e) {
            return new Response(500, ['error' => "Файл с таким id не найден: " . $e->getMessage()]);
        }
    }

    public function addFilesRep(string $fileName, ?int $derectId = null, int $subDerectId = null, ?int $isShared = null): ?Response
    {
        $this->checkSession();
        try {
            $secondName = uniqid() . '_' . $fileName;
            $sql = "INSERT INTO `files`(`id`, `file_name`,`second_name`,`derectories_id`, `sub_derectories_id`, `is_shared`) VALUES (null, :file_name, :second_name, :derectories_id, :sub_derectories_id, :is_shared)";
            $params = [':file_name' => $fileName, ':second_name' => $secondName, ':derectories_id' => $derectId, ':sub_derectories_id' => $subDerectId, ':is_shared' => $isShared];
            $this->db->findBy($sql, $params);
        } catch (Exception $e) {
            return new Response(500, ['error' => "Не удалось добавить файл: " . $e->getMessage()]);
        }
    }

    public function renameFiles(int $id, string $fileNewName): ?Response
    {
        $this->checkSession();
        try {
            $sql = "UPDATE `files` SET `file_name`= :file_name WHERE `id`= :id";
            $params = [':id' => $id, ':file_name' => $fileNewName];
            $this->db->findBy($sql, $params);
        } catch (Exception $e) {
            return new Response(500, ['error' => "Не удалось переименовать файл: " . $e->getMessage()]);
        }
    }

    public function updateDirectFiles(int $id, int $newDerect): ?Response
    {
        $this->checkSession();
        try {
            $sql = "UPDATE `files` SET `derectories_id`= :derectories_id WHERE `id`= :id";
            $params = [':id' => $id, ':derectories_id' => $newDerect];
            $this->db->findBy($sql, $params);
        } catch (Exception $e) {
            return new Response(500, ['error' => "Не удалось переименовать файл: " . $e->getMessage()]);
        }
    }

    public function updateSubDirectFiles(int $id, int $newSubDerect): ?Response
    {
        $this->checkSession();
        try {
            $sql = "UPDATE `files` SET `sub_derectories_id`= :sub_derectories_id WHERE `id`= :id";
            $params = [':id' => $id, ':sub_derectories_id' => $newSubDerect];
            $this->db->findBy($sql, $params);
        } catch (Exception $e) {
            return new Response(500, ['error' => "Не удалось переименовать файл: " . $e->getMessage()]);
        }
    }

    public function removeFilesRep(int $id): ?Response
    {
        try {
            $sql = "DELETE FROM `files` WHERE `id` = :id";
            $params = [':id' => $id];
            $this->db->findBy($sql, $params);
        } catch (Exception $e) {
            return new Response(500, ['error' => "Не удалось удалить файл: " . $e->getMessage()]);
        }
    }

    public function getDerectories(int $id): array
    {
        $this->checkSession();

        try {
            $sql = "SELECT `id`, `derectoriesName` FROM `derectories` WHERE id = :id";
            $params = [':id' => $id];
            $result = $this->db->find($sql, $params);

            if (!$result) {
                return new Response(404, ['error' => "Directory with id $id not found"]);
            }

            $sql = "SELECT `file_name` FROM `files` WHERE `derectories_id` = :id";
            $params = [':id' => $id];
            $files = $this->db->findAll($sql, $params);

            $sql = "SELECT f.`file_name` 
                    FROM `sub_derectories` sd
                    JOIN `files` f ON sd.`id` = f.`sub_derectories_id`
                    WHERE sd.`parent_id` = :id";
            $params = [':id' => $id];
            $subDirectoriesFiles = $this->db->findAll($sql, $params);

            $sql = "SELECT `id`, `sub_name` FROM `sub_derectories` WHERE `parent_id` = :id";
            $params = [':id' => $id];
            $subDirectories = $this->db->findAll($sql, $params);

            $result['files'] = $files;
            $result['sub_directories'] = $subDirectories;
            $result['sub_directories_files'] = $subDirectoriesFiles;

            return $result;
        } catch (Exception $e) {
            return new Response(500, ['error' => "Failed to get directory: " . $e->getMessage()]);
        }
    }


    public function addDerectories(string $directoriesName): ?Response
    {
        $this->checkSession();

        try {
            $sql = "INSERT INTO `derectories`(`id`, `derectoriesName`) VALUES (null, :derect_name)";
            $params = [':derect_name' => $directoriesName];
            $this->db->findBy($sql, $params);
        } catch (Exception $e) {
            return new Response(500, ['error' => "Failed to add directory: " . $e->getMessage()]);
        }
    }

    private function directoryExists($directoryId): bool
    {
        $sql = "SELECT COUNT(*) FROM `derectories` WHERE `id` = :id";
        $params = [':id' => $directoryId];

        try {
            $stmt = $this->db->findBy($sql, $params);
            return $stmt->fetchColumn() > 0;
        } catch (Exception) {
            return false;
        }
    }

    public function addSubdirectory(string $subdirectoryName, int $parent_id): ?Response
    {
        $this->checkSession();

        if (!$this->directoryExists($parent_id)) {
            return new Response(404, ['error' => "Parent directory with id=$parent_id does not exist."]);
        }

        try {
            $sql = "INSERT INTO `sub_derectories`(`id`, `sub_name`, `parent_id`) VALUES (null, :subdir_name, :parent_id)";
            $params = [':subdir_name' => $subdirectoryName, ':parent_id' => $parent_id];
            $this->db->findBy($sql, $params);
        } catch (Exception $e) {
            return new Response(500, ['error' => "Failed to add subdirectory: " . $e->getMessage()]);
        }
    }

    public function renameDerectories(int $id, string $DerectNewName): ?Response
    {
        $this->checkSession();
        try {
            $sql = "UPDATE `derectories` SET `derectoriesName`= :derect_name WHERE `id`= :id";
            $params = [':id' => $id, ':derect_name' => $DerectNewName];
            $this->db->findBy($sql, $params);
        } catch (Exception $e) {
            return new Response(500, ['error' => "Не удалось переименовать папку: " . $e->getMessage()]);
        }
    }

    public function removeDerectories(int $id): ?Response
    {
        $this->checkSession();
        try {
            $sql = "DELETE FROM `derectories` WHERE `id` = :id";
            $params = [':id' => $id];
            $this->db->findBy($sql, $params);
        } catch (Exception $e) {
            return new Response(500, ['error' => "Не удалось удалить папку: " . $e->getMessage()]);
        }
    }

    public function hasUserAccessToFile(int $fileId, int $userId): array
    {
        try {
            $sql = "SELECT COUNT(*) FROM `user_files` WHERE `file_id` = :file_id AND `user_id` = :user_id";
            $params = [':file_id' => $fileId, ':user_id' => $userId];
            $stmt = $this->db->findBy($sql, $params);
            $count = $stmt->fetchColumn();
            return ['has_access' => $count > 0];
        } catch (Exception $e) {
            return ['error' => "Failed to check file access: " . $e->getMessage()];
        }
    }


    public function downloadFiles(int $id): ?Response
    {
        $this->checkSession();

        $currentUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        try {
            $sql = "SELECT `file_name`, `second_name` FROM `files` WHERE `id` = :id";
            $params = [':id' => $id];
            $file = $this->db->find($sql, $params);

            $accessData = $this->hasUserAccessToFile($id, $currentUserId);

            if ($accessData['has_access'] === true) {
                if ($file) {
                    $filePath = 'C:\Users\romat\Desktop\file\\' . $file['file_name'];

                    if (file_exists($filePath)) {
                        ob_clean();
                        ob_start();
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename="' . rawurlencode(basename($file['second_name'])) . '"');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($filePath));
                        readfile($filePath);

                        ob_end_flush();
                        exit;
                    } else {
                        return new Response(404, ['error' => 'Файл не найден.', 'file_path' => $filePath]);
                    }
                }
            } else {
                return new Response(500, ['error' => "Нет доступа: "]);
            }
        } catch (Exception $e) {
            return new Response(500, ['error' => "Не удалось скачать файл: " . $e->getMessage()]);
        }
    }

    public function fileAccess(int $fileId, int $userId): ?Response
    {
        $this->checkSession();

        try {
            $sql = "INSERT INTO user_files (file_id, user_id) VALUES (:file_id, :user_id)";
            $params = [':file_id' => $fileId, ':user_id' => $userId];
            $this->db->findBy($sql, $params);

            $sql = "UPDATE files SET is_shared = 1 WHERE id = :file_id";
            $params = [':file_id' => $fileId];
            $this->db->findBy($sql, $params);
        } catch (Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }
    public function removeFileAccess(int $fileId, int $userId): ?Response
    {
        $this->checkSession();

        try {
            $sql = "DELETE FROM user_files WHERE file_id = :file_id AND user_id = :user_id";
            $params = [':file_id' => $fileId, ':user_id' => $userId];
            $this->db->findBy($sql, $params);
        } catch (Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }
    public function getUserFile(int $fileId): array
    {
        $this->checkSession();

        try {
            $sql = "SELECT `email`, `age` FROM users JOIN user_files ON users.id = user_files.user_id WHERE user_files.file_id = :file_id";
            $params = [':file_id' => $fileId];
            return  $this->db->findAll($sql, $params);
        } catch (Exception) {
            return [];
        }
    }
}
