function getDocsPins() {
    var data_file = "json/comunas.json";
    var xhr = new XMLHttpRequest();
    try {
        xhr = new XMLHttpRequest();
    } catch (e) {
        try {
            xhr = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                alert("Your browser broke!");
                return false;
            }
        }
    }

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
        var container = document.getElementById("container");
        var jsonObj = JSON.parse(xhr.responseText);
        console.log(jsonObj.length);

        javascrypt_array.forEach(element => {
            var marker = L.marker([element.lat, element.lng]).addTo(mymap);
            marker.bindPopup(element.name, {maxWidth: "auto"});
        })

        }
    }
    xhr.open("GET", data_file);
    xhr.send();
}
