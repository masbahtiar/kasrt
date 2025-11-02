"use strict";

var app = angular
    .module("myApp", [
        "angularFileUpload",
        "colorbox",
        "ang-dialogs",
        "ngAnimate",
        "toaster"
    ])
    .constant("CSRF_TOKEN", csrf)
    .factory("Sekolah", function($http) {
        var service = {};
        service.getListKtgUpload = function() {
            return $http.get(apiUrl + "/upload/lsktgupload/");
        };
        service.getListKelengkapan = function(id_sekolah) {
            return $http.get(apiUrl + "/upload/lskelengkapan/" + id_sekolah);
        };
        service.getSekolah = function(id_sekolah) {
            return $http.get(apiUrl + "/upload/sekolah/" + id_sekolah);
        };
        service.delImage = function(id) {
            return $http.post(apiUrl + "/upload/delimg/" + id);
        };
        return service;
    })

    .controller("AppController", function(
        $scope,
        FileUploader,
        Sekolah,
        $dialogs,
        toaster
    ) {
        var uploader = ($scope.uploader = new FileUploader({
            url: apiUrl + "/sekolah/upload",
            removeAfterUpload: true
        }));
        $scope.ktgUploads = null;
        $scope.curKtgUpload = null;
        $scope.kelengkapans = [];
        $scope.uploadUrl = uploudUrl;
        $scope.message = { success: "", cancel: "", error: "" };
        var loadKtgUpload = function() {
            Sekolah.getListKtgUpload().then(function(response) {
                $scope.ktgUploads = response.data;
                $scope.curKtgUpload = response.data[0];
            });
        };
        var loadKelengkapan = function(id_sekolah) {
            Sekolah.getListKelengkapan(id_sekolah).then(function(response) {
                $scope.kelengkapans = response.data;
            });
        };
        var updateMessage = function(type, message) {
            if (type == "error") {
                $scope.message.error = message;
            } else if (type == "success") {
                $scope.message.success = message;
            }
        };

        loadKtgUpload();
        loadKelengkapan(id_sekolah);
        // FILTERS
        $scope.delImg = function(id) {
            $dialogs.showConfirmationDialog("Apakah gambar dihapus ?", {
                title: "Hapus Data",
                buttonOkText: "Ya",
                buttonCancelText: "Tidak",
                callback: function(option) {
                    if (option === "ok") {
                        Sekolah.delImage(id).then(function(response) {
                            console.log(response.data);
                            loadKelengkapan(id_sekolah);
                        });
                    }
                }
            });
        };
        uploader.filters.push(
            {
                name: "imageFilter",
                fn: function(item /*{File|FileLikeObject}*/, options) {
                    var type =
                        "|" +
                        item.type.slice(item.type.lastIndexOf("/") + 1) +
                        "|";
                    return "|jpg|png|jpeg|bmp|gif|".indexOf(type) !== -1;
                }
            },
            {
                name: "imageSize",
                fn: function(item /*{File|FileLikeObject}*/, options) {
                    const fsize = item.size;
                    const file = Math.round(fsize / 1024);
                    // The size of the file.
                    return file <= 1024;
                }
            }
        );

        // CALLBACKS

        uploader.onWhenAddingFileFailed = function(
            item /*{File|FileLikeObject}*/,
            filter,
            options
        ) {
            console.info("onWhenAddingFileFailed", item, filter, options);
            if (filter.name === "imageSize") {
                toaster.pop(
                    "error",
                    "Error Upload File",
                    "<span>Ukuran File Maksimal 1 Mb<span>",
                    4000,
                    "trustedHtml"
                );
            } else if (filter.name === "imageFilter") {
                toaster.pop(
                    "error",
                    "Error Upload File",
                    "<span>Jenis File Tidak Didukung<span>",
                    4000,
                    "trustedHtml"
                );
            }
        };
        uploader.onAfterAddingFile = function(fileItem) {
            // console.info("onAfterAddingFile", fileItem);
            fileItem.ktgUpload = $scope.curKtgUpload;
        };
        uploader.onAfterAddingAll = function(addedFileItems) {
            // console.info("onAfterAddingAll", addedFileItems);
        };
        uploader.onBeforeUploadItem = function(item) {
            console.info("onBeforeUploadItem", item);
            item.formData.push({ _token: csrf });
            item.formData.push({ id_ktgupload: item.ktgUpload.id });
            item.formData.push({ id_sekolah: id_sekolah });
        };
        uploader.onProgressItem = function(fileItem, progress) {
            // console.info("onProgressItem", fileItem, progress);
        };
        uploader.onProgressAll = function(progress) {
            // console.info("onProgressAll", progress);
        };
        uploader.onSuccessItem = function(fileItem, response, status, headers) {
            // console.info("onSuccessItem", fileItem, response, status, headers);
            // fileItem.remove();
        };
        uploader.onErrorItem = function(fileItem, response, status, headers) {
            // console.info("onErrorItem", fileItem, response, status, headers);
            updateMessage("error", response.message);
        };
        uploader.onCancelItem = function(fileItem, response, status, headers) {
            // console.info("onCancelItem", fileItem, response, status, headers);
        };
        uploader.onCompleteItem = function(
            fileItem,
            response,
            status,
            headers
        ) {
            //            console.info('onCompleteItem', fileItem, response, status, headers);
            loadKelengkapan(id_sekolah);
        };
        uploader.onCompleteAll = function() {
            loadKelengkapan(id_sekolah);
        };

        console.info("uploader", uploader);
    });
