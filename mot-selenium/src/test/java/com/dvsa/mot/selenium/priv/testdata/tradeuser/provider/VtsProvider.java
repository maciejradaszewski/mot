package com.dvsa.mot.selenium.priv.testdata.tradeuser.provider;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;
import com.dvsa.mot.selenium.framework.api.helper.Emailifier;
import com.dvsa.mot.selenium.priv.testdata.tradeuser.entity.VtsSimple;

public class VtsProvider {

    private Login managementLogin;
    private VtsCreationApi vtsCreationApi;

    public VtsProvider(Login managementLogin) {
        this.managementLogin = managementLogin;
        this.vtsCreationApi = new VtsCreationApi();
    }

    /**
     * @param aeId
     * @param vts
     * @return id of created VTS
     */
    public int createVts(int aeId, VtsSimple vts) {
        return vtsCreationApi.createVts(
                new VtsCreationApi.VtsData().aeId(aeId).classes(vts.getClasses())
                        .name(vts.getName()).addressLine1(vts.getAddressLine1())
                        .email(Emailifier.fromString(vts.getName())), managementLogin);
    }
}
