(function($){

    acf.add_action('ready', function(){

        const latField = $('[name="acf[field_699541437f26f]"]');
        const lngField = $('[name="acf[field_699541517f270]"]');

        if (!latField.length || !lngField.length) return;

        const lat = latField.val() || 50.4501;
        const lng = lngField.val() || 30.5234;

        const map = L.map('admin-map').setView([lat, lng], 13);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap & CartoDB'
        }).addTo(map);

        const marker = L.marker([lat, lng], {draggable:true}).addTo(map);

        marker.on('dragend', function() {
            const pos = marker.getLatLng();
            latField.val(pos.lat);
            lngField.val(pos.lng);
        });

        map.on('click', function(e){
            marker.setLatLng(e.latlng);
            latField.val(e.latlng.lat);
            lngField.val(e.latlng.lng);
        });

        $('#geocode-btn').on('click', function(){

            const country = $('[name="acf[field_699540c77f269]"]').val();
            const city = $('[name="acf[field_6995411c7f26b]"]').val();
            const street = $('[name="acf[field_699541307f26d]"]').val();
            const house = $('[name="acf[field_699541377f26e]"]').val();

            const address = `${country} ${city} ${street} ${house}`;

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        const lat = data[0].lat;
                        const lon = data[0].lon;

                        marker.setLatLng([lat, lon]);
                        map.setView([lat, lon], 15);

                        latField.val(lat);
                        lngField.val(lon);
                    }
                });

        });

    });

})(jQuery);
