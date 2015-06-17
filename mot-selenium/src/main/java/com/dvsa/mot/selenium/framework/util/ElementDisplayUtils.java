package com.dvsa.mot.selenium.framework.util;


import com.dvsa.mot.selenium.framework.util.predicate.IsDisplayedPredicate;
import org.apache.commons.collections.CollectionUtils;
import org.openqa.selenium.WebElement;

import java.util.Arrays;

public class ElementDisplayUtils {

    public static boolean elementsDisplayed(WebElement[] elements) {
        return CollectionUtils.countMatches(Arrays.asList(elements), new IsDisplayedPredicate())
                == elements.length;
    }
}
