import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, PointElement, LineElement } from 'chart.js';
import ChartDataLabels from "chartjs-plugin-datalabels";


ChartJS.register(ChartDataLabels, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, PointElement, LineElement)

import {Line, Bar, Doughnut} from "vue-chartjs";

export default {
    components: {
        Line,
        Bar,
        Doughnut
    },
}
