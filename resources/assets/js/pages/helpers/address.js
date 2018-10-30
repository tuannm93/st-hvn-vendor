var Address = function () {

    /**
     * Get Address By ZipCode
     * @param url
     * @param zipcode
     * @param callback
     */
    function getAddressByZipCode(url, zipcode, callback) {
        if (!(typeof url == "undefined" || url == null)) {
            if (!(typeof zipcode == "undefined" || zipcode == null)) {
                url = url + '?zip=' + zipcode;
                $.ajax({
                    type : "GET",
                    url : url,
                    cache : false,
                    success : callback,
                    error : function() {
                        alert("Error");
                    }
                });
            }
        }
    }

    return {
        getAddressByZipCode: getAddressByZipCode
    };
}();