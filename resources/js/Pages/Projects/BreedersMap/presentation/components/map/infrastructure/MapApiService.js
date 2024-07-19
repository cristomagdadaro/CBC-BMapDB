import ApiService from "@/Modules/core/infrastructure/ApiService.js";
import {ref} from "vue";
import BaseRequest from "@/Modules/core/infrastructure/BaseRequest.js";

export default class MapApiService{
    constructor(baseUrl, model = Object) {
        this.api = new ApiService(baseUrl);
        this.model = ref(model);
        this.response = ref(null);
        const localParams = BaseRequest.getParamsLocal();
        this.request = localParams? new BaseRequest(localParams) : new BaseRequest();


        this._zoom = 2;
        this._center = [12.296167, 122.763835];
        this._minZoom = 5.9;
        this._maxZoom = 15;
        this.maxBound = [
            [4.225, 116.9],
            [19.5, 130.5]
        ]; /*{
            southwest: [3.834376, 116.071894],
            northeast: [21.777897, 127.345418]
        }*/
        this._markerLatLng = null;
        this._selectedPlace = null;
        this._sidebarVisible = false;

        this._mapOptions = {
            zoomControl: true,
            attributionControl: false,
            maxBoundsViscosity: 1,
            zoomAnimation: true,
            fadeAnimation: true,
            markerZoomAnimation: true,
            zoomAnimationThreshold: 4,
            doubleClickZoom: true,
            keyboard: true,
            closePopupOnClick: false,
            dragging: true,
            touchZoom: true,
            scrollWheelZoom: true,
            tap: true
        };
    }

    async init() {
        await this.refresh();
    }

    get sidebarVisible() {
        return this._sidebarVisible;
    }

    set sidebarVisible(value) {
        this._sidebarVisible = value;
    }

    get mapOptions() {
        return this._mapOptions;
    }

    set mapOptions(value) {
        this._mapOptions = value;
    }
    get maxZoom() {
        return this._maxZoom;
    }

    set maxZoom(value) {
        this._maxZoom = value;
    }
    get processing() {
        return this.api.processing;
    }

    set processing(value) {
        this.api.processing = value;
    }

    get zoom() {
        return this._zoom;
    }

    set zoom(value) {
        this._zoom = value;
    }

    get center() {
        return this._center;
    }

    set center(value) {
        this._center = value;
    }

    get minZoom() {
        return this._minZoom;
    }

    set minZoom(value) {
        this._minZoom = value;
    }

    get markerLatLng() {
        return this._markerLatLng;
    }

    set markerLatLng(value) {
        this._markerLatLng = value;
    }

    get selectedPlace() {
        return this._selectedPlace;
    }

    set selectedPlace(value) {
        this._selectedPlace = value;
    }

    async refresh() {
        this.response = await this.api.get(this.request.toObject(), this.model);
    }

    getDataPoint() {
        if (this.response && this.response.data)
        return this.response.data;
    }

    async updateParam(params) {
        for (const key in params) {
            this.request.updateParam(key, params[key]);
        }
        await this.refresh();
    }

    selectPoint(point) {
        this._markerLatLng = [point.latitude, point.longitude];
        this._selectedPlace = point;
        this.updateCenter(this._markerLatLng);
        this.updateZoom(8);
        this.sidebarVisible = true; // open the sidebar on point selection
    }

    updateCenter(center) {
        this._center = center;
    }

    updateZoom(zoom) {
        if (this._zoom === zoom)
            return;
        if (zoom >= this._minZoom && zoom <= this._maxZoom)
            this._zoom = zoom;
    }

    isPointSelected(point) {
        return this._markerLatLng && this._markerLatLng[0] === point.latitude && this._markerLatLng[1] === point.longitude;
    }

    deselectPoint() {
        this._markerLatLng = null;
        this._selectedPlace = null;
        this._sidebarVisible = false;
        this.updateZoom(this._minZoom);
    }
}
