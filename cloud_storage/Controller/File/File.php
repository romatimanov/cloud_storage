<?php

namespace App\Controller\File;

use App\Core\Response\Response;
use App\Repositories\FilesRepositories\FilesInterface;

class File
{
    private $fileRep;

    public function __construct(FilesInterface $fileRepository)
    {
        $this->fileRep = $fileRepository;
    }

    public function list(): Response
    {
        try {
            $data = $this->fileRep->list();
            return new Response(['data' => $data], 200);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function getFiles($id): Response
    {
        try {
            $data = $this->fileRep->getFiles($id);
            return new Response(['data' => $data], 200);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function addFiles(): Response
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $fileName = $data['file_name'];
            $this->fileRep->addFilesRep($fileName);
            return new Response(200, ['message' => 'File added successfully']);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function renameFiles(): Response
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $id = $data['id'];
            $fileNewName = $data['file_name'];
            $this->fileRep->renameFiles($id, $fileNewName);
            return new Response(200, ['message' => 'File renamed successfully']);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function updateDirectFiles(): Response
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $id = $data['id'];
            $newDerect = $data['derect_id'];
            $this->fileRep->updateDirectFiles($id, $newDerect);
            return new Response(200, ['message' => 'File directory updated successfully']);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function updateSubDirectFiles(): Response
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $id = $data['id'];
            $newDerect = $data['derect_id'];
            $this->fileRep->updateSubDirectFiles($id, $newDerect);
            return new Response(200, ['message' => 'File directory updated successfully']);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function removeFiles(): Response
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $id = $data['id'];
            $this->fileRep->removeFilesRep($id);
            return new Response(200, ['message' => 'File removed successfully']);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function getDerectories($id): Response
    {
        try {
            $data = $this->fileRep->getDerectories($id);
            return new Response(['data' => $data], 200);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function addDerectories(): Response
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $directoriesName = $data['derect_name'];
            $this->fileRep->addDerectories($directoriesName);
            return new Response(200, ['message' => 'Directory added successfully']);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function addSubdirectory(): Response
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $subdirectoryName = $data['subDerect_name'];
            $parent_id = $data['subParent_id'];
            $this->fileRep->addSubdirectory($subdirectoryName, $parent_id);
            return new Response(200, ['message' => 'Subdirectory added successfully']);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function renameDerectories(): Response
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $id = $data['id'];
            $DerectNewName = $data['derect_name'];
            $this->fileRep->renameDerectories($id, $DerectNewName);
            return new Response(200, ['message' => 'Directory renamed successfully']);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function removeDerectories($id): Response
    {
        try {
            $this->fileRep->removeDerectories($id);
            return new Response(200, ['message' => 'Directory removed successfully']);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function downloadFiles($id): Response
    {
        try {
            $this->fileRep->downloadFiles($id);
            return new Response(200, ['message' => 'File download initiated']);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function fileAccess(): Response
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $fileId = $data['file_id'];
            $userId = $data['user_id'];
            $this->fileRep->fileAccess($fileId, $userId);
            return new Response(200, ['message' => 'File access granted successfully']);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function removeFileAccess(): Response
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $fileId = $data['file_id'];
            $userId = $data['user_id'];
            $this->fileRep->removeFileAccess($fileId, $userId);
            return new Response(200, ['message' => 'File access removed successfully']);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function getUserFile(int $fileId): Response
    {
        try {
            $data = $this->fileRep->getUserFile($fileId);
            return new Response(['data' => $data], 200);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }
}
