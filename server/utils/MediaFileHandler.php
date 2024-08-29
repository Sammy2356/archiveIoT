<?php

namespace app\utils;


class MediaFileHandler
{

    private $base_url = "../public/src/";
    private $valid_extension = array("png", "jpeg", "jpg", "pdf");
    private $dest_url = [];
    private $set = false;
    private $mediaService;

    function __construct()
    {
        if (!file_exists($this->base_url)) {
            mkdir($this->base_url, 0777, true);
        }
    }

    public function singleFileUpload($file): bool
    {
        if ($file["size"] > 10) {
            $this->imageProcessor($file["tmp_name"], $file["name"]);
            return count($this->dest_url) > 0;
        }
        $this->dest_url = [""];
        return true;
    }

    public function multipleFileUpload($files): bool
    {
        $file_count = count($files["name"]);
        for ($index = 0; $index < $file_count; $index++) {
            $this->imageProcessor($files["tmp_name"][$index], $files["name"][$index]);
        }
        return count($this->dest_url) > 0;
    }


    public function sealUpload(int $resource_id): bool // Old
    {
        // upload dest_url to media table
        $status = false;
        foreach ($this->dest_url as $key => $url) {
            # call the upload media service
            $payload = array(
                "media_resource_id" => $resource_id,
                "media_url" => $url
            );
            $status = $this->mediaService->setMedia($payload);
        }
        return $status;
    }

    public function getMediaUrl(): string
    {
        return $this->dest_url[0];
    }

    public function deleteFiles($filesUrl): bool
    {
        $status = false;

        if (isset($filesUrl) && is_string($filesUrl) && strlen(trim($filesUrl)) > 10) {

            $files = explode(",", $filesUrl);

            foreach ($files as $key => $single) {
                if (unlink($single)) {
                    $status = true;
                }
            }
            return $status;
        }
        return true;
    }

    // private function
    private function getImageExtension($image_file)
    {
        return strtolower(pathinfo($image_file, PATHINFO_EXTENSION));
    }

    private function generateName(): string
    {
        return "src_" . uniqid(time());
    }

    private function imageProcessor($tmp, $file): void
    {
        $file_extension = $this->getImageExtension($file);
        $target_file = $this->base_url . $this->generateName() . "." . $file_extension;
        if (in_array($file_extension, $this->valid_extension)) {
            if (move_uploaded_file($tmp, $target_file)) {
                // $this->dest_url = $target_file;
                array_push($this->dest_url, $target_file);
                $this->set = true;
            }
        }
    }
}
