import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import { redrawMapPinMarkers } from './map-pin-markers';

window.mapPinManager = (wire, pins) => {
    let leafletState;

    return {
        filter: '',
        mapStatus: '',
        pins,

        init() {
            const mapElement = this.$refs.map;
            leafletState = mapElement.__mapPinManagerState;

            if (leafletState) {
                leafletState.wire = wire;
                this.renderPins();

                return;
            }

            const map = L.map(mapElement, {
                keyboard: true,
            }).setView([20, 0], 2);

            leafletState = {
                map,
                markers: new Map(),
                wire,
            };
            mapElement.__mapPinManagerState = leafletState;

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19,
            }).addTo(map);

            map.on('click', (event) => {
                leafletState.wire.beginCreating(event.latlng.lat, event.latlng.lng);
            });

            this.renderPins();
            this.fitPins();
        },

        replacePins(pins) {
            this.pins = pins;
            this.renderPins();
        },

        renderPins() {
            redrawMapPinMarkers(
                leafletState.markers,
                this.pins,
                leafletState.map,
                (pin) => {
                    const marker = L.marker([pin.latitude, pin.longitude], {
                        alt: pin.comment ? `Map pin: ${pin.comment}` : 'Map pin',
                        keyboard: true,
                    });

                    marker.on('click', () => {
                        leafletState.wire.selectPin(pin.id);
                    });

                    return marker;
                },
            );

            this.filterPins();
        },

        filterPins() {
            if (!leafletState) {
                return;
            }

            const filter = this.filter.trim().toLocaleLowerCase();

            leafletState.markers.forEach(({ marker, pin }) => {
                const comment = (pin.comment ?? '').toLocaleLowerCase();
                const visible = filter === '' || comment.includes(filter);

                if (visible && !leafletState.map.hasLayer(marker)) {
                    marker.addTo(leafletState.map);
                }

                if (!visible && leafletState.map.hasLayer(marker)) {
                    marker.remove();
                }
            });
        },

        fitPins() {
            if (this.pins.length === 0) {
                return;
            }

            const bounds = L.latLngBounds(this.pins.map((pin) => [pin.latitude, pin.longitude]));
            leafletState.map.fitBounds(bounds, { padding: [32, 32], maxZoom: 14 });
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

                    leafletState.map.setView(location, 14);
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
            if (!leafletState) {
                return 0;
            }

            return Array.from(leafletState.markers.values()).filter(({ marker }) => leafletState.map.hasLayer(marker)).length;
        },
    };
};
