//app = angular.module('myApp');
app.config(function($routeProvider, $locationProvider) {
    $routeProvider
        .when("/:id", {
            templateUrl: "../../../templates3/ruang-verifikasi.html",
            controller: "ruangCtrl"
        })
        .when("/subruang/:id", {
            templateUrl: "../../../templates3/subruang.html",
            controller: "subRuangCtrl"
        })
        .when("/pilihruang/:id", {
            templateUrl: "../../../templates3/pilihruang.html",
            controller: "pilihRuangCtrl"
        })
        .when("/:id/itemruang/:idnmruang", {
            templateUrl: "../../../templates3/itemruang-verifikasi.html",
            controller: "itemRuangCtrl"
        })
        .when("/:id/itemsubruang/:idnmruang", {
            templateUrl: "../../../templates3/itemsubruang.html",
            controller: "itemSubRuangCtrl"
        })
        .when(
            "/:id/itemruang/:idnmruang/uploadkerusakan/:ruang_sekolah_id/item/:item_ruang_id",
            {
                templateUrl:
                    "../../../templates3/uploadkerusakan-verifikasi.html",
                controller: "uploadKerusakanCtrl"
            }
        )
        .otherwise({
            redirectTo: "/:id"
        });
    $locationProvider.html5Mode(true);
});

app.controller("ruangCtrl", function($scope, $route, Sekolah, $routeParams) {
    $scope.pageClass = "page-home";
    $scope.id = $routeParams.id;
    $scope.nmruangs = {};
    Sekolah.getListNmRuang($scope.id).then(function(response) {
        $scope.nmruangs = response.data;
    });
});
app.controller("subRuangCtrl", function($scope, $route, Sekolah, $routeParams) {
    $scope.pageClass = "page-about";
    $scope.id = $routeParams.id;
    $scope.subruangs = {};
    Sekolah.getListSubRuang($scope.id).then(function(response) {
        $scope.subruangs = response.data;
    });
});
app.controller("pilihRuangCtrl", function(
    $scope,
    $route,
    Sekolah,
    $routeParams
) {
    $scope.pageClass = "page-about";
    $scope.id = $routeParams.id;
    $scope.subruangs = {};
    Sekolah.getListPilihRuang($scope.id).then(function(response) {
        $scope.subruangs = response.data;
    });
    $scope.updPilihRuang = function() {
        Sekolah.updPilihRuang($scope.subruangs).then(function(response) {});
        //console.log($scope.subruangs);
    };
});
app.controller("itemRuangCtrl", function(
    $scope,
    $route,
    Sekolah,
    $routeParams,
    $location
) {
    $scope.pageClass = "page-contact";
    $scope.itemruangs = {};
    $scope.id = $routeParams.id;
    $scope.idnmruang = $routeParams.idnmruang;
    $scope.ruangSekolah = {};
    $scope.nilai = [];
    $scope.verifikasi = [];
    $scope.hasil = [];
    $scope.hsl_verifikasi = [];

    Sekolah.getListItemRuang($scope.id, $scope.idnmruang).then(function(
        response
    ) {
        $scope.itemruangs = response.data.itemRuang;
        $scope.ruangSekolah = response.data.ruangSekolah;
        $scope.nmRuang = response.data.nmRuang;
        angular.forEach($scope.itemruangs, function(v, i) {
            $scope.nilai[i] = v.nilai;
            $scope.verifikasi[i] = v.verifikasi;
            $scope.hasil[i] = v.hasil;
            $scope.hsl_verifikasi[i] = v.hsl_verifikasi;
        });
    });
    $scope.updRuangSekolah = function() {
        $scope.ruangSekolah.nilai = $scope.nilai.toString();
        $scope.ruangSekolah.verifikasi = $scope.verifikasi.toString();
        var isvalid = 0;
        var hasil = 0;
        var hsl_verifikasi = 0;
        angular.forEach($scope.itemruangs, function(v, i) {
            hasil += (parseFloat($scope.nilai[i]) * parseFloat(v.bobot)) / 100;
            hsl_verifikasi +=
                (parseFloat($scope.verifikasi[i]) * parseFloat(v.bobot)) / 100;
            if ($scope.verifikasi[i] > 100) {
                isvalid += 1;
            }
        });

        $scope.ruangSekolah.hasil = hasil;
        $scope.ruangSekolah.hsl_verifikasi = hsl_verifikasi;
        if (isvalid > 0) {
            bootbox.alert("Nilai tidak valid, masimal 100");
        } else {
            Sekolah.updRuangSekolah($scope.ruangSekolah).then(function(
                response
            ) {
                $location.path("/" + response.data.id_sekolah);
            });
        }
    };
});

app.controller("itemSubRuangCtrl", function(
    $scope,
    $route,
    Sekolah,
    $routeParams,
    $location
) {
    $scope.pageClass = "page-contact";
    $scope.itemruangs = {};
    $scope.id = $routeParams.id;
    $scope.idnmruang = $routeParams.idnmruang;
    $scope.ruangSekolah = {};
    $scope.nilai = [];
    $scope.verifikasi = [];
    $scope.hasil = [];
    $scope.hsl_verifikasi = [];
    $scope.nmRuang = {};

    Sekolah.getListItemRuang($scope.id, $scope.idnmruang).then(function(
        response
    ) {
        $scope.itemruangs = response.data.itemRuang;
        $scope.ruangSekolah = response.data.ruangSekolah;
        $scope.nmRuang = response.data.nmRuang;
        angular.forEach($scope.itemruangs, function(v, i) {
            $scope.nilai[i] = v.nilai;
            $scope.verifikasi[i] = v.verifikasi;
            $scope.hasil[i] = v.hasil;
            $scope.hsl_verifikasi[i] = v.hsl_verifikasi;
        });
    });

    $scope.updRuangSekolah = function() {
        $scope.ruangSekolah.nilai = $scope.nilai.toString();
        $scope.ruangSekolah.verifikasi = $scope.verifikasi.toString();
        var hasil = 0;
        var hsl_verifikasi = 0;
        angular.forEach($scope.itemruangs, function(v, i) {
            hasil += (parseFloat($scope.nilai[i]) * parseFloat(v.bobot)) / 100;
            hsl_verifikasi +=
                (parseFloat($scope.verifikasi[i]) * parseFloat(v.bobot)) / 100;
        });

        $scope.ruangSekolah.hasil = hasil;
        $scope.ruangSekolah.hsl_verifikasi = hsl_verifikasi;
        Sekolah.updRuangSekolah($scope.ruangSekolah).then(function(response) {
            $location.path("/subruang/" + response.data.id_sekolah);
        });
    };
});

app.controller("indexController", function($scope, $route) {});

app.controller("uploadKerusakanCtrl", function(
    $scope,
    FileUploader,
    $route,
    $routeParams,
    Sekolah,
    $dialogs,
    $location
) {
    $scope.pageClass = "page-contact";
    $scope.datas = { message: "upload file kerusakan" };
    $scope.id = $routeParams.id;
    $scope.idnmruang = $routeParams.idnmruang;
    $scope.ruang_sekolah_id = $routeParams.ruang_sekolah_id;
    $scope.item_ruang_id = $routeParams.item_ruang_id;
    $scope.kerusakans = [];
    $scope.uploadUrl = `${uploadUrl}${$routeParams.ruang_sekolah_id}/${$routeParams.item_ruang_id}/`;
    $scope.judul = "";
    $scope.errorMessage = "";

    var uploader = ($scope.uploader = new FileUploader({
        url: apiUrl + "/ruang_sekolah/upload"
    }));
    uploader.filters.push({
        name: "imageFilter",
        fn: function(item /*{File|FileLikeObject}*/, options) {
            var type =
                "|" + item.type.slice(item.type.lastIndexOf("/") + 1) + "|";
            return "|jpg|png|jpeg|bmp|gif|".indexOf(type) !== -1;
        }
    });
    var loadKerusakan = function(ruang_sekolah_id, item_ruang_id) {
        Sekolah.getListKerusakan(ruang_sekolah_id, item_ruang_id).then(function(
            response
        ) {
            $scope.kerusakans = response.data;
        });
    };
    updateErrorMessage = msg => {
        $scope.errorMessage = msg;
    };

    $scope.delImgKerusakan = function(id) {
        $dialogs.showConfirmationDialog("Apakah gambar dihapus ?", {
            title: "Hapus Data",
            buttonOkText: "Ya",
            buttonCancelText: "Tidak",
            callback: function(option) {
                if (option === "ok") {
                    Sekolah.delImageKerusakan(id).then(function(response) {
                        console.log(response.data);
                        loadKerusakan(
                            $scope.ruang_sekolah_id,
                            $scope.item_ruang_id
                        );
                    });
                }
            }
        });
    };

    loadKerusakan($scope.ruang_sekolah_id, $scope.item_ruang_id);

    uploader.onWhenAddingFileFailed = function(
        item /*{File|FileLikeObject}*/,
        filter,
        options
    ) {
        console.info("onWhenAddingFileFailed", item, filter, options);
    };
    uploader.onAfterAddingFile = function(fileItem) {
        console.info("onAfterAddingFile", fileItem);
    };
    uploader.onAfterAddingAll = function(addedFileItems) {
        console.info("onAfterAddingAll", addedFileItems);
    };
    uploader.onBeforeUploadItem = function(item) {
        console.info("onBeforeUploadItem", item);
        if ($scope.judul == "") {
            updateErrorMessage("Judul/Keterangan harus diisi dulu");
            item.cancel();
            return;
        }
        item.formData.push({ _token: csrf });
        item.formData.push({ judul: $scope.judul });
        item.formData.push({ ruang_sekolah_id: $scope.id });
        item.formData.push({ item_ruang_id: $scope.item_ruang_id });
    };
    uploader.onProgressItem = function(fileItem, progress) {
        console.info("onProgressItem", fileItem, progress);
    };
    uploader.onProgressAll = function(progress) {
        console.info("onProgressAll", progress);
    };
    uploader.onSuccessItem = function(fileItem, response, status, headers) {
        // console.info("onSuccessItem", fileItem, response, status, headers);
        fileItem.remove();
    };
    uploader.onErrorItem = function(fileItem, response, status, headers) {
        console.info("onErrorItem", fileItem, response, status, headers);
    };
    uploader.onCancelItem = function(fileItem, response, status, headers) {
        console.info("onCancelItem", fileItem, response, status, headers);
    };
    uploader.onCompleteItem = function(fileItem, response, status, headers) {
        console.info("onCompleteItem", fileItem, response, status, headers);
    };
    uploader.onCompleteAll = function() {
        loadKerusakan($scope.ruang_sekolah_id, $scope.item_ruang_id);
    };

    console.info("uploader", uploader);
});
