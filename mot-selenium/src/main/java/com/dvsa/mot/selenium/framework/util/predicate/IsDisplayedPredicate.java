package com.dvsa.mot.selenium.framework.util.predicate;


import org.apache.commons.collections.Predicate;
import org.openqa.selenium.WebElement;

public class IsDisplayedPredicate implements Predicate {

    public boolean evaluate(Object el) {
        return ((WebElement) el).isDisplayed();
    }
}
