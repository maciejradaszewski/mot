package com.dvsa.mot.selenium.framework.util.validation;


import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;

import java.util.List;

public class ValidationSummary {

    public static boolean isValidationSummaryDisplayed(WebDriver webDriver) {

        final String VALIDATION_SUMMARY_SELECTOR = "//*[@id='validation-summary-id']//li";
        List<WebElement> elements = webDriver.findElements(By.xpath(VALIDATION_SUMMARY_SELECTOR));
        return elements.size() > 0;
    }
}
