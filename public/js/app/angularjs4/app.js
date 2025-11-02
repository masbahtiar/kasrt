var app = angular.module("myApp", [
    "ngRoute",
    "ngAnimate",
    "angularFileUpload",
    "colorbox",
    "ang-dialogs",
    "toaster"
]);

app.constant("CSRF_TOKEN", csrf);
app.factory("Sekolah", function($http) {
    var service = {};
    service.getListNmRuang = function(id_sekolah) {
        return $http.get(apiUrl + "/nmruang/list/" + id_sekolah);
    };
    service.getListSubRuang = function(id_sekolah) {
        return $http.get(apiUrl + "/nmruang/listsubruang/" + id_sekolah);
    };
    service.getListItemRuang = function(id_sekolah, id_nmruang) {
        return $http.get(
            apiUrl + "/itemruang/list/" + id_sekolah + "/" + id_nmruang
        );
    };
    service.getListPilihRuang = function(id_sekolah) {
        return $http.get(apiUrl + "/nmruang/listpilihruang/" + id_sekolah);
    };
    service.updRuangSekolah = function(frmdata) {
        return $http.post(apiUrl + "/ruangsekolah/add", frmdata);
    };
    service.updPilihRuang = function(frmdata) {
        return $http.post(apiUrl + "/nmruang/updpilihruang", frmdata);
    };
    service.removeRuang = function(frmdata) {
        return $http.post(apiUrl + "/ruangsekolah/remove", frmdata);
    };
    service.getListKerusakan = function(ruang_sekolah_id, item_ruang_id) {
        return $http.get(
            apiUrl +
                "/upload/lskerusakan/" +
                ruang_sekolah_id +
                "/" +
                item_ruang_id
        );
    };
    service.delImageKerusakan = function(id) {
        return $http.post(apiUrl + "/upload/delimgkerusakan/" + id);
    };

    return service;
});

app.directive("numbersOnly", function() {
    return {
        require: "ngModel",
        link: function(scope, element, attr, ngModelCtrl) {
            function fromUser(text) {
                if (text) {
                    var transformedInput = text.replace(/[^0-9]/g, "");

                    if (transformedInput !== text) {
                        ngModelCtrl.$setViewValue(transformedInput);
                        ngModelCtrl.$render();
                    }
                    return transformedInput;
                }
                return undefined;
            }
            ngModelCtrl.$parsers.push(fromUser);
        }
    };
});
