package uk.gov.dvsa.helper;

import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResultsPageField;
import org.openqa.selenium.By;
import org.openqa.selenium.NoSuchElementException;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.ui.Select;

import java.util.Map;

public class FormCompletionHelper {

    public static void selectFromDropDownByValue(WebElement element, String value){
        Select dropdown = new Select(element);
        dropdown.selectByValue(value);
    }

    public static void selectFromDropDownByVisibleText(WebElement element, String value){
        Select dropdown = new Select(element);
        dropdown.selectByVisibleText(value);
    }

    public static void selectInputBox(WebElement webElement) {
        if(webElement.isDisplayed() && !webElement.isSelected()) {
            webElement.click();
        }
    }

    public static void enterText(WebElement webElement, String value) {
        webElement.clear();
        webElement.sendKeys(value);
    }
}
