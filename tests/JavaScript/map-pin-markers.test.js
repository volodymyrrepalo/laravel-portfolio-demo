import assert from 'node:assert/strict';
import test from 'node:test';
import { redrawMapPinMarkers } from '../../resources/js/map-pin-markers.js';

test('redrawMapPinMarkers removes cached markers before replacing the current pins', () => {
    const map = {};
    const previousMarker = marker();
    const markers = new Map([[1, { marker: previousMarker, pin: { id: 1 } }]]);
    const firstPin = { id: 1 };
    const secondPin = { id: 2 };

    redrawMapPinMarkers(markers, [firstPin, secondPin], map, marker);

    assert.equal(previousMarker.removed, true);
    assert.equal(markers.size, 2);
    assert.equal(markers.get(1).pin, firstPin);
    assert.equal(markers.get(2).pin, secondPin);

    const firstReplacement = markers.get(1).marker;
    const secondReplacement = markers.get(2).marker;

    redrawMapPinMarkers(markers, [secondPin], map, marker);

    assert.equal(firstReplacement.removed, true);
    assert.equal(secondReplacement.removed, true);
    assert.equal(markers.size, 1);
    assert.equal(markers.get(2).pin, secondPin);
});

function marker() {
    return {
        removed: false,
        addTo(map) {
            this.map = map;
        },
        remove() {
            this.removed = true;
        },
    };
}
