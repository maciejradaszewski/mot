package com.dvsa.mot.selenium.priv.frontend.equipment;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.priv.frontend.equipment.pages.EquipmentPage;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;


public class ListOfEquipments extends BaseTest {

    @Test(groups = "VM-2619") public void testListOfEquipmentDisplayedSuccessful() {

        LoginPage.loginAs(driver, Login.LOGIN_SCHEME_MANAGEMENT);
        EquipmentPage equipmentsList = EquipmentPage.navigateToEquipmentPage(driver);
        assertThat("Equipment Table Displayed", equipmentsList.isEquipmentTableDisplayed(),
                is(true));
        assertThat("Equipment Table has data",
                equipmentsList.isAnyRecoredsPresentInEquipmentTable(), is(true));
    }

}
