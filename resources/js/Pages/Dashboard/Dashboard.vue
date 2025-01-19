<template>
    <div ref="dashboardsContainer"></div>
</template>

<script setup>
// Import necessary Vue functions
import { ref, watch } from 'vue';

// Import Highcharts, Dashboards and Dashboard Plugins
import Dashboards from '@highcharts/dashboards';
import DataGrid from '@highcharts/dashboards/datagrid';
import LayoutModule from '@highcharts/dashboards/modules/layout';
import Highcharts from 'highcharts';

LayoutModule(Dashboards);

Dashboards.HighchartsPlugin.custom.connectHighcharts(Highcharts);
Dashboards.DataGridPlugin.custom.connectDataGrid(DataGrid);

Dashboards.PluginHandler.addPlugin(Dashboards.HighchartsPlugin);
Dashboards.PluginHandler.addPlugin(Dashboards.DataGridPlugin);

// Define props and the Dashboards container
const props = defineProps(['config']);
const dashboardsContainer = ref(null);

// Once the container is added to the DOM, render the Dashboard
// with the config given in props
watch(dashboardsContainer, () => {
    if (dashboardsContainer.value) {
        Dashboards.board(dashboardsContainer.value, props.config, true);
    }
});
</script>
