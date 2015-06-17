package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.AdvisoryLocation;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.Select;

public class AdvisoryLocationPage extends BasePage {

    @FindBy(id = "modal-rfr-title-0") private WebElement title;

    @FindBy(id = "lateral-dd-7089") private WebElement lateral;

    @FindBy(id = "longitudinal-dd-7089") private WebElement longitudinal;

    @FindBy(id = "vertical-dd-7089") private WebElement vertical;

    @FindBy(id = "description-7089") private WebElement description;

    @FindBy(id = "dangerous") private WebElement dangerousFailure;

    @FindBy(id = "rfr-submit-7089") private WebElement addButton;

    @FindBy(id = "rfr-cancel-7089") private WebElement cancelButton;

    public AdvisoryLocationPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

}
