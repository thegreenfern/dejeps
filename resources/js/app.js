import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

// Fix default marker icon paths broken by bundlers
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: new URL('leaflet/dist/images/marker-icon-2x.png', import.meta.url).href,
    iconUrl:       new URL('leaflet/dist/images/marker-icon.png',   import.meta.url).href,
    shadowUrl:     new URL('leaflet/dist/images/marker-shadow.png', import.meta.url).href,
});

document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('sites-map');
    if (!el) return;

    var sites = JSON.parse(el.dataset.sites || '[]');

    var map = L.map('sites-map').setView([42.5667, 8.7667], 12);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    sites.forEach(function (site) {
        var popup = '<strong>' + site.nom + '</strong>';
        if (site.profondeur) popup += '<br><span style="color:#64748b;font-size:12px">↓ ' + site.profondeur + ' m</span>';
        if (site.niveau)     popup += ' &nbsp;<span style="color:#64748b;font-size:12px">' + site.niveau + '</span>';
        if (site.description) popup += '<br><span style="color:#64748b;font-size:12px">' + site.description + '</span>';
        L.marker([site.lat, site.lng]).addTo(map).bindPopup(popup);
    });
});
