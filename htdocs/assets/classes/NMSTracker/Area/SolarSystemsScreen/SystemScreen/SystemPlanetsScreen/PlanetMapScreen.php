<?php

declare(strict_types=1);

namespace NMSTracker\Area\SolarSystemsScreen\SystemScreen\SystemPlanetsScreen;

use Application_Admin_Area_Mode_Submode_Action_CollectionList;
use AppUtils\ClassHelper;
use AppUtils\ConvertHelper\JSONConverter;
use AppUtils\HTMLTag;
use AppUtils\NamedClosure;
use AppUtils\OutputBuffering;
use Closure;
use DBHelper_BaseCollection;
use DBHelper_BaseFilterCriteria_Record;
use DBHelper_BaseRecord;
use NMSTracker;
use NMSTracker\ClassFactory;
use NMSTracker\Interfaces\Admin\ViewPlanetScreenInterface;
use NMSTracker\Interfaces\Admin\ViewPlanetScreenTrait;
use NMSTracker\Interfaces\Admin\ViewSystemScreenTrait;
use NMSTracker\PlanetPOIs\PlanetPOIFilterCriteria;
use NMSTracker\PlanetPOIs\PlanetPOIRecord;
use NMSTracker\PlanetPOIsCollection;
use NMSTracker\PlanetsCollection;
use NMSTracker\SolarSystemsCollection;
use UI;
use UI_DataGrid_Action;
use UI_DataGrid_Action_Confirm;
use UI_Themes_Theme_ContentRenderer;

/**
 * @property PlanetPOIFilterCriteria $filters
 */
class PlanetMapScreen
    extends Application_Admin_Area_Mode_Submode_Action_CollectionList
    implements ViewPlanetScreenInterface
{
    use ViewPlanetScreenTrait;
    use ViewSystemScreenTrait;

    public const URL_NAME = 'map';
    private string $jsID;

    public function getURLName() : string
    {
        return self::URL_NAME;
    }

    /**
     * @return PlanetPOIsCollection
     */
    protected function createCollection() : DBHelper_BaseCollection
    {
        return ClassFactory::createPlanetPOIs();
    }

    protected function configureFilters() : void
    {
        $this->filters->selectPlanet($this->getPlanet());
    }

    protected function getEntryData(DBHelper_BaseRecord $record, DBHelper_BaseFilterCriteria_Record $entry) : array
    {
        return array();
    }

    protected function configureColumns() : void
    {
    }

    protected function configureActions() : void
    {
        $this->jsID = nextJSID();
    }

    protected function _renderContent() : UI_Themes_Theme_ContentRenderer
    {
        return $this->renderer
            ->appendContent($this->renderMap())
            ->makeWithSidebar();
    }

    protected function _handleSidebar() : void
    {
        $this->sidebar->addButton('add-poi', t('Add a POI...'))
            ->makeLinked($this->getPlanet()->getAdminCreatePOIURL())
            ->setIcon(UI::icon()->add());

        $this->sidebar->addSeparator();

        $this->sidebar->addSection()
            ->setTitle(t('Player position'))
            ->setIcon(NMSTracker::icon()->coordinates())
            ->setContent($this->renderPlayerCoordsForm());
    }

    public function getBackOrCancelURL() : string
    {
        return $this->getSolarSystem()->getAdminStatusURL();
    }

    public function getNavigationTitle() : string
    {
        return t('Map');
    }

    public function getTitle() : string
    {
        return t('Planetary map');
    }

    public function getAbstract() : string
    {
        return '';
    }

    // region: Map generation

    /**
     * The "labels" key is used by the chart labels plugin, and
     * lists the labels for all data points in the order they appear.
     *
     * @var string
     */
    private string $chartData = <<<'EOT'
const mapData = {
  labels: %2$s,
  datasets: %1$s
};
EOT;

    private string $chartConfig = <<<'EOT'
const mapConfig = {
  type: 'scatter',
  data: mapData,
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top'
      }
    },
    scales: {
      y: {
        title: {
          display: true,
          text: 'Latitude'
        },
        type: 'linear',
        position: 'left',
        min: %1$s,
        max: %2$s,
        ticks: {
          color: '#000000'
        }
      },
      x: {
        title: {
          display: true,
          text: 'Longitude'
        },
        type: 'linear',
        min: %3$s,
        max: %4$s
      }
    }
  },
}
EOT;

    /**
     * @var string[]
     */
    private array $mapLabels = array();

    private function compileMapData() : string
    {
        $data = array();

        $poiData = array(
            'dataID' => 'pois',
            'label' => t('POIs'),
            'data' => array(),
            'borderColor' => '#cc0000',
            'backgroundColor' => '#cc0000',
            'yAxisID' => 'y',
        );

        $pois = $this->filters->getItemsObjects();
        foreach($pois as $poi)
        {
            $this->mapLabels[] = $poi->getLabel();

            $poiData['data'][] = array(
                'x' => $poi->getLongitude(),
                'y' => $poi->getLatitude()
            );
        }

        $outpostData = array(
            'dataID' => 'outposts',
            'label' => t('Outposts'),
            'data' => array(),
            'borderColor' => '#ccee00',
            'backgroundColor' => '#ccee00',
            'yAxisID' => 'y',
        );

        $outposts = $this->getPlanet()->getOutposts();
        foreach($outposts as $outpost)
        {
            $coords = $outpost->getCoordinates();

            if($coords === null) {
                continue;
            }

            $this->mapLabels[] = $outpost->getLabel();

            $outpostData['data'][] = array(
                'x' => $outpost->getLongitude(),
                'y' => $outpost->getLatitude()
            );
        }

        $data[] = $poiData;
        $data[] = $outpostData;

        $data[] = array(
            'dataID' => 'player',
            'label' => t('Player'),
            'data' => array(),
            'borderColor' => '#00ccee',
            'backgroundColor' => '#00ccee',
            'yAxisID' => 'y'
        );

        return JSONConverter::var2json($data, JSON_PRETTY_PRINT);
    }

    private function renderMap() : string
    {
        $this->ui->addJavascript('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.1.2/chart.umd.min.js')
            ->setTypeModule()
            ->setIntegrity('sha512-KTyzZ0W6S8dUq9WIt8fSflj2ArYRGcGNIU5QcB1skGGd1EPFMupHZSexEsFFX18tZK4eO0iGGSZGuyrNIqjV8g==')
            ->setCrossOriginAnonymous()
            ->setReferrerPolicyNone();

        $this->ui->addJavascript('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.1.2/chart.min.js')
            ->setTypeModule()
            ->setIntegrity('sha512-fYE9wAJg2PYbpJPxyGcuzDSiMuWJiw58rKa9MWQICkAqEO+xeJ5hg5qPihF8kqa7tbgJxsmgY0Yp51+IMrSEVg==')
            ->setCrossOriginAnonymous()
            ->setReferrerPolicyNone();

        $this->ui->addJavascript('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.1.2/helpers.min.js')
            ->setTypeModule()
            ->setIntegrity('sha512-JG3S/EICkp8Lx9YhtIpzAVJ55WGnxT3T6bfiXYbjPRUoN9yu+ZM+wVLDsI/L2BWRiKjw/67d+/APw/CDn+Lm0Q==')
            ->setCrossOriginAnonymous()
            ->setReferrerPolicyNone();

        $this->ui->addJavascript('map.js');

        $this->ui->addJavascriptHead(sprintf(
            $this->chartData,
            $this->compileMapData(),
            JSONConverter::var2json($this->mapLabels, JSON_PRETTY_PRINT)
        ));

        $this->ui->addJavascriptHead(sprintf(
            "const poiMapManager = new MapManager('%s')",
            $this->jsID
        ));

        $scale = $this->getPlanet()->getMapScale();
        $latitude = ceil($scale->getLatitude()->getValue() + $scale->getLatitude()->addPercent(5)->getValue());
        $longitude = ceil($scale->getLongitude()->getValue() + $scale->getLongitude()->addPercent(5)->getValue());

        // Must be on load to work properly.
        $this->ui->addJavascriptOnload(sprintf(
            $this->chartConfig,
            $latitude * -1, // ymin
            $latitude, // ymax
            $longitude * -1, // xmin
            $longitude // xmax
        ));

        $this->ui->addJavascriptOnload(sprintf(
            "const poiMap = new Chart(document.getElementById('%s'), mapConfig)",
            $this->jsID
        ));

        $this->ui->addJavascriptOnload("poiMapManager.SetChart(poiMap)");

        return (string)
            HTMLTag::create('canvas')
                ->setEmptyAllowed()
                ->attr('style', 'width:800px;height:520px')
                ->attr('id', $this->jsID);
    }

    private function renderPlayerCoordsForm() : string
    {
        OutputBuffering::start();
        ?>
        <form class="form-inline" onsubmit="poiMapManager.ShowPlayerPosition();return false;">
            <p>
            <label for="<?php echo $this->jsID ?>-longitude" style="width: 25%">
                <?php pt('Longitude') ?>
            </label>
            <input type="number" id="<?php echo $this->jsID ?>-longitude" style="width: 60px">
            </p>
            <p>
            <label for="<?php echo $this->jsID ?>-latitude" style="width: 25%">
                <?php pt('Latitude') ?>
            </label>
            <input type="number" id="<?php echo $this->jsID ?>-latitude" style="width: 60px">
            </p>
            <p>
            <?php
            echo UI::button(t('Show'))
                ->makeSubmit('show-button', 'yes')
                ->setIcon(UI::icon()->view())
                ->makeSmall()
                ->setTooltip(t('Shows the player\'s position on the map.'));
            ?>
            </p>
        </form>
        <?php

        return OutputBuffering::get();
    }

    // endregion
}
