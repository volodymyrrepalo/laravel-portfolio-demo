/**
 * Replace Leaflet markers with the latest pins returned by Livewire.
 *
 * @param {Map<number, {marker: {remove: () => void}, pin: object}>} markers
 * @param {Array<{id: number}>} pins
 * @param {{}} map
 * @param {(pin: {id: number}) => {addTo: (map: {}) => void}} createMarker
 * @returns {void}
 */
export function redrawMapPinMarkers(markers, pins, map, createMarker) {
    markers.forEach(({ marker }) => marker.remove());
    markers.clear();

    pins.forEach((pin) => {
        const marker = createMarker(pin);

        marker.addTo(map);
        markers.set(pin.id, { marker, pin });
    });
}
