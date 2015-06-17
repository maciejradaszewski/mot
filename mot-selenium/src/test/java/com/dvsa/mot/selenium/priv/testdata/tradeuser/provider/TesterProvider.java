package com.dvsa.mot.selenium.priv.testdata.tradeuser.provider;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.api.TesterCreationApi;
import com.dvsa.mot.selenium.framework.api.helper.Emailifier;
import com.dvsa.mot.selenium.framework.api.helper.UsernameCreator;
import com.dvsa.mot.selenium.priv.testdata.tradeuser.entity.TesterSimple;

import java.util.List;

public class TesterProvider {

    private Login managementLogin;
    private TesterCreationApi testerCreationApi;

    public TesterProvider(Login managementLogin) {
        this.managementLogin = managementLogin;
        this.testerCreationApi = new TesterCreationApi();
    }

    /**
     * @param tester
     * @param vtsIds
     * @return
     */
    public Login createTester(TesterSimple tester, List<Integer> vtsIds) {
        return testerCreationApi.createTester(
                new TesterCreationApi.TesterData().status(tester.getStatus()).vtsIds(vtsIds)
                        .firstName(tester.getFirstName()).surname(tester.getSurname())
                        .username(tester.getUsername()).emailAddress(Emailifier.fromString(
                                UsernameCreator.fromPersonName(tester.getFirstName(),
                                        tester.getSurname())))
                        .addressLine1(tester.getAddressLine1()).phoneNumber(tester.getPhoneNumber())
                        .accountClaimRequired(tester.getAccountClaimRequired()), managementLogin);
    }
}
