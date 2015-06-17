package com.dvsa.mot.selenium.priv.frontend.equipment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class EquipmentPage extends BasePage {

    private static final String URL = baseUrl() + "/equipment";

    @FindBy(id = "masterEquipmentList") private WebElement equipmentTable;

    public EquipmentPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public boolean isEquipmentTableDisplayed() {
        return isElementDisplayed(equipmentTable);
    }

    public boolean isAnyRecoredsPresentInEquipmentTable() {

        return (driver.findElements(By.xpath("//table[@id='masterEquipmentList']/tbody/tr")).size())
                > 1;
    }

    public static EquipmentPage navigateToEquipmentPage(WebDriver driver) {

        driver.get(URL);
        return new EquipmentPage(driver);
    }
}	

