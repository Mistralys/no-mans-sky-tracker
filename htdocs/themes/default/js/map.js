class MapManager
{
    /**
     * @param {String} mapCanvasID
     */
    constructor(mapCanvasID)
    {
        this.canvasID = mapCanvasID;
    }

    /**
     * @param {Chart} chart
     */
    SetChart(chart)
    {
        this.chart = chart;

        console.log('POIMap | The chart instance has been set.');
    }

    ShowPlayerPosition()
    {
        console.log('POIMap | Showing player position.');

        let playerCoords = {
            x: this.GetLongitude(),
            y: this.GetLatitude()
        };

        if(isNaN(playerCoords.x) || isNaN(playerCoords.y)) {
            console.log('POIMap | Invalid coordinate values, ignoring.');
            return;
        }

        console.log('POIMap | Player coordinates: '+playerCoords.x + ' / '+playerCoords.y);

        this.chart.data.datasets.forEach(dataset =>
        {
            if(dataset.dataID === 'player')
            {
                console.log('POIMap | Player data found, injecting.');

                dataset.data = [playerCoords];
            }
        });

        console.log('POIMap | Updating the chart.');

        this.chart.update();
    }

    GetLongitude()
    {
        return parseFloat($('#'+this.canvasID+'-longitude').val());
    }

    GetLatitude()
    {
        return parseFloat($('#'+this.canvasID+'-latitude').val());
    }
}
