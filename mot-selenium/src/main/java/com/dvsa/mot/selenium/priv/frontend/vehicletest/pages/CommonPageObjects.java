package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;

public class CommonPageObjects extends BasePage {

    By testerVTSInfo = By.id("user-info");

    //private final WebDriver driver;
    public CommonPageObjects(WebDriver driver) {
        super(driver);
    }

    @SuppressWarnings("finally")
    public static boolean isTextPresent(WebDriver driver, String txtValue) {
        boolean b = false;
        try {
            b = driver.getPageSource().contains(txtValue);
            return b;
        } catch (Exception e) {
            System.out.println(e.getMessage());
        } finally {
            return b;
        }
    }
    //TODO - check creation class with checking header
    // e.g checkHeade(user,vts,etc){
    //	  PageFactory.initElements(driver, this);
    //	this.driver = driver;
    //	return true/fasle
    //		}


}

