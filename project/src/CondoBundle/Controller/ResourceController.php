<?php

namespace CondoBundle\Controller;

use CondoBundle\Traits\HasRepositories;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResourceController extends Controller
{
    use HasRepositories;

    /**
     * Gets a file form the database.
     *
     * @Route("/{fileId}", name="condo_resource")
     *
     * @param int $fileId
     *
     * @return StreamedResponse
     */
    public function getResourceAction($fileId)
    {
        $tmpPath = '/tmp/condo_media_'.$fileId;
        $tmpFile = new File($tmpPath, false);

        // Only lookup the database if the tmp file does not exists.
        if (!file_exists($tmpPath)) {
            $file = $this->getDatabaseFileRepository()
                ->find($fileId);
            file_put_contents($tmpPath, $file->getData());
        }

        $response = new StreamedResponse(
            function () use ($tmpPath) {
                echo stream_get_contents(readfile($tmpPath));
            }
        );
        $response->headers->set('Content-Type', $tmpFile->getMimeType());

        return $response;
    }
}
