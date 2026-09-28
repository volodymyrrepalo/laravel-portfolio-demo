import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

window.mapPinManager = (wire, pins) => ({
    filter: '',
    map: null,
    mapStatus: '',
    markers: new Map(),
    pins,

    init() {
        this.map = L.map(this.$refs.map, {
            keyboard: true,
        }).setView([20, 0], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19,
        }).addTo(this.map);

        this.map.on('click', (event) => {
            wire.beginCreating(event.latlng.lat, event.latlng.lng);
        });

        this.renderPins();
        this.fitPins();
    },

    replacePins(pins) {
        this.pins = pins;
        this.renderPins();
    },

    renderPins() {
        this.markers.forEach((marker) => marker.remove());
        this.markers.clear();

        this.pins.forEach((pin) => {
            const marker = L.marker([pin.latitude, pin.longitude], {
                alt: pin.comment ? `Map pin: ${pin.comment}` : 'Map pin',
                keyboard: true,
            });

            marker.on('click', () => {
                wire.selectPin(pin.id);
            });

            marker.addTo(this.map);
            this.markers.set(pin.id, { marker, pin });
        });

        this.filterPins();
    },

    filterPins() {
        const filter = this.filter.trim().toLocaleLowerCase();

        this.markers.forEach(({ marker, pin }) => {
            const comment = (pin.comment ?? '').toLocaleLowerCase();
            const visible = filter === '' || comment.includes(filter);

            if (visible && !this.map.hasLayer(marker)) {
                marker.addTo(this.map);
            }

            if (!visible && this.map.hasLayer(marker)) {
                marker.remove();
            }
        });
    },

    fitPins() {
        if (this.pins.length === 0) {
            return;
        }

        const bounds = L.latLngBounds(this.pins.map((pin) => [pin.latitude, pin.longitude]));
        this.map.fitBounds(bounds, { padding: [32, 32], maxZoom: 14 });
    },

    useCurrentLocation() {
        if (!navigator.geolocation) {
            this.mapStatus = 'Location is not available in this browser.';

            return;
        }

        this.mapStatus = 'Finding your location…';

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const location = [position.coords.latitude, position.coords.longitude];

                this.map.setView(location, 14);
                this.mapStatus = 'Map centered on your location.';
            },
            () => {
                this.mapStatus = 'We could not use your location. You can still pan or zoom the map.';
            },
            {
                enableHighAccuracy: false,
                maximumAge: 300000,
                timeout: 10000,
            },
        );
    },

    visiblePinCount() {
        return Array.from(this.markers.values()).filter(({ marker }) => this.map.hasLayer(marker)).length;
    },
});
