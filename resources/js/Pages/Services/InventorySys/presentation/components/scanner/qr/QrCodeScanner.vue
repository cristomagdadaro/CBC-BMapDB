<script>
import {QrcodeStream} from "vue-qrcode-reader";
import CustomDropdown from "@/Components/CustomDropdown/CustomDropdown.vue";
import FilterIcon from "@/Components/Icons/FilterIcon.vue";

export default {
    name: "QrCodeScanner",
    components: {FilterIcon, CustomDropdown, QrcodeStream},
    computed: {
        selectedBarcodeFormats() {
            return Object.keys(this.barcodeFormats.value).filter((format) => this.barcodeFormats.value[format])
        }
    },
    methods: {
        onDetect(detectedCodes) {
            console.log(detectedCodes)
            this.result = JSON.stringify(detectedCodes.map((code) => code.rawValue))
        },
        paintOutline(detectedCodes, ctx) {
            for (const detectedCode of detectedCodes) {
                const [firstPoint, ...otherPoints] = detectedCode.cornerPoints

                ctx.strokeStyle = 'red'

                ctx.beginPath()
                ctx.moveTo(firstPoint.x, firstPoint.y)
                for (const { x, y } of otherPoints) {
                    ctx.lineTo(x, y)
                }
                ctx.lineTo(firstPoint.x, firstPoint.y)
                ctx.closePath()
                ctx.stroke()
            }
        },
        paintBoundingBox(detectedCodes, ctx) {
            for (const detectedCode of detectedCodes) {
                const {
                    boundingBox: { x, y, width, height }
                } = detectedCode

                ctx.lineWidth = 2
                ctx.strokeStyle = '#007bff'
                ctx.strokeRect(x, y, width, height)
            }
        },
        paintCenterText(detectedCodes, ctx) {
            for (const detectedCode of detectedCodes) {
                const { boundingBox, rawValue } = detectedCode

                const centerX = boundingBox.x + boundingBox.width / 2
                const centerY = boundingBox.y + boundingBox.height / 2

                const fontSize = Math.max(12, (50 * boundingBox.width) / ctx.canvas.width)

                ctx.font = `bold ${fontSize}px sans-serif`
                ctx.textAlign = 'center'

                ctx.lineWidth = 3
                ctx.strokeStyle = '#35495e'
                ctx.strokeText(detectedCode.rawValue, centerX, centerY)

                ctx.fillStyle = '#5cb984'
                ctx.fillText(rawValue, centerX, centerY)
            }
        },
        onError(err) {
            error.value = `[${err.name}]: `

            if (err.name === 'NotAllowedError') {
                error.value += 'you need to grant camera access permission'
            } else if (err.name === 'NotFoundError') {
                error.value += 'no camera on this device'
            } else if (err.name === 'NotSupportedError') {
                error.value += 'secure context required (HTTPS, localhost)'
            } else if (err.name === 'NotReadableError') {
                error.value += 'is the camera already in use?'
            } else if (err.name === 'OverconstrainedError') {
                error.value += 'installed cameras are not suitable'
            } else if (err.name === 'StreamApiNotSupportedError') {
                error.value += 'Stream API is not supported in this browser'
            } else if (err.name === 'InsecureContextError') {
                error.value +=
                    'Camera access is only permitted in secure context. Use HTTPS or localhost rather than HTTP.'
            } else {
                error.value += err.message
            }
        },
    },
    data() {
        return {
            devices: [],
            selectedDevice: null,
            trackFunctionSelected: { text: 'outline', value: this.paintOutline },
            barcodeFormats: {
                aztec: true,
                code_128: true,
                code_39: true,
                code_93: true,
                codabar: true,
                databar: true,
                databar_expanded: true,
                data_matrix: true,
                dx_film_edge: true,
                ean_13: true,
                ean_8: true,
                itf: true,
                maxi_code: true,
                micro_qr_code: true,
                pdf417: true,
                qr_code: true,
                rm_qr_code: true,
                upc_a: true,
                upc_e: true,
                linear_codes: true,
                matrix_codes: true
            },
            result: '',
            error: '',
            trackFunctionOptions: [
                { text: 'nothing (default)', value: undefined },
                { text: 'outline', value: this.paintOutline },
                { text: 'centered text', value: this.paintCenterText },
                { text: 'bounding box', value: this.paintBoundingBox }
            ]
        }
    },
    async mounted() {
        await navigator.mediaDevices.enumerateDevices()
            .then((devices) => {
                this.devices = devices.filter((device) => device.kind === 'videoinput')
                if (this.devices.length > 0) {
                    this.selectedDevice = this.devices[0]
                }
            })
            .catch((error) => {
                this.error = error
            })
    },
}
</script>

<template>
    <div>
        <p>
            <div class="flex flex-col gap-0.5">
                <span class="text-xs text-gray-500 flex items-center justify-between">
                    <span class="flex gap-0.5">Camera</span>
                </span>
                <custom-dropdown
                    :value="selectedDevice"
                    placeholder="Choose a camera"
                    :options="devices">
                    <template #icon>
                        <filter-icon class="h-4 w-4" />
                    </template>
                </custom-dropdown>
            </div>
        </p>

        <p>
            <div class="flex flex-col gap-0.5">
                <span class="text-xs text-gray-500 flex items-center justify-between">
                    <span class="flex gap-0.5">Mode</span>
                </span>
                <custom-dropdown
                    :value="trackFunctionSelected"
                    placeholder="Choose a camera"
                    :options="trackFunctionOptions">
                    <template #icon>
                        <filter-icon class="h-4 w-4" />
                    </template>
                </custom-dropdown>
            </div>
        </p>

        <p>
            Detectable Format:

            <span
                v-for="option in Object.keys(barcodeFormats)"
                :key="option"
                class="barcode-format-checkbox"
            >
        <input
            type="checkbox"
            v-model="barcodeFormats[option]"
            :id="option"
        />
        <label :for="option">{{ option }}</label>
      </span>
        </p>

        <p class="error">{{ error }}</p>

        <p class="decode-result">
            Detected Value{{ result.length>1?'s':'' }}: <b>{{ result }}</b>
        </p>

        <div class="w-1/3">
            <qrcode-stream
                :constraints="{ deviceId: selectedDevice.deviceId }"
                :track="trackFunctionSelected.value"
                :formats="selectedBarcodeFormats"
                @error="onError"
                @detect="onDetect"
                v-if="selectedDevice !== null"
            />
            <p
                v-else
                class="error"
            >
                No cameras on this device
            </p>
        </div>
    </div>
</template>

<style scoped>
.error {
    font-weight: bold;
    color: red;
}
.barcode-format-checkbox {
    margin-right: 10px;
    white-space: nowrap;
}
</style>
