class PlanetList
{
    /**
     * @constructor
     */
    constructor()
    {
        this.ERROR_SCAN_STATE_REQUEST_FAILED = '131301';
    }

    /**
     * @param {String} containerID
     * @param {String} methodName
     */
    RegisterToggle(containerID, methodName)
    {
        let list = this;
        let info = {
            'methodName': methodName,
            'planetID':$('#'+containerID).attr('data-planet-id'),
            'elON':$('#'+containerID+' .state-on'),
            'elOFF':$('#'+containerID+' .state-off')
        };

        info.elON.dblclick(function() {
            list.SetStateOff(info);
        });

        info.elOFF.dblclick(function() {
            list.SetStateOn(info);
        });
    }

    SetStateOff(planetInfo)
    {
        this.SendStatusChangeRequest(planetInfo, 'false');
    }

    SetStateOn(planetInfo)
    {
        this.SendStatusChangeRequest(planetInfo, 'true');
    }

    SendStatusChangeRequest(planetInfo, complete)
    {
        let list = this;
        let payload = {
            'state':complete,
            'planet_id':planetInfo.planetID
        };

        application.createAJAX(planetInfo.methodName)
            .SetPayload(payload)
            .Success(function (data) {
                list.HandleStateChanged(planetInfo, data);
            })
            .Error(
                t('Could not change the state.'),
                this.ERROR_SCAN_STATE_REQUEST_FAILED
            )
            .Retry(function() {
                list.SendStatusChangeRequest(planetInfo, complete);
            })
            .Send();
    }

    HandleStateChanged(planetInfo, data)
    {
        if(data.state === 'true')
        {
            planetInfo.elON.show();
            planetInfo.elOFF.hide();
        }
        else
        {
            planetInfo.elON.hide();
            planetInfo.elOFF.show();
        }
    }
}
