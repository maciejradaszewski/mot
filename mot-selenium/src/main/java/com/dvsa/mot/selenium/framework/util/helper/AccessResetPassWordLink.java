package com.dvsa.mot.selenium.framework.util.helper;


import com.dvsa.mot.selenium.priv.frontend.user.ResetPasswordPage;
import org.openqa.selenium.WebDriver;

import static com.dvsa.mot.selenium.framework.Configurator.baseUrl;

public class AccessResetPassWordLink {

    public static ResetPasswordPage goToResetPassWordPage(WebDriver driver, String token) {

        driver.get(baseUrl() + "/forgotten-password" + "/reset/" + token);
        return new ResetPasswordPage(driver);
    }
}
