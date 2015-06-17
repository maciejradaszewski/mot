package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.PRSLocation;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.Select;

public class PRSLocationPage extends BasePage {

    @FindBy(id = "modal-rfr-title-0") private WebElement title;

    @FindBy(id = "lateral-dd-1055") private WebElement lateral;

    @FindBy(id = "longitudinal-dd-1055") private WebElement longitudinal;

    @FindBy(id = "vertical-dd-1055") private WebElement vertical;

    @FindBy(id = "description-1055") private WebElement description;

    @FindBy(id = "dangerous") private WebElement dangerousFailure;

    @FindBy(id = "rfr-submit-1055") private WebElement addButton;

    @FindBy(id = "rfr-cancel-1055") private WebElement cancelButton;

    public PRSLocationPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

}
