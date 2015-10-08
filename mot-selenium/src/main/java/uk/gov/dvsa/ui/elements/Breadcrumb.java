package uk.gov.dvsa.ui.elements;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

public class Breadcrumb {

    private static final String SELECTOR = "global-breadcrumb";

    private WebElement rootElement;

    public Breadcrumb(MotAppDriver driver) {
        List<WebElement> elements = driver.findElements(By.id(SELECTOR));
        this.rootElement = elements.size() != 0 ? elements.get(0) : null;
    }

    public List<String> asList() {

        if (rootElement == null) return Collections.emptyList();
        List<String> list = new ArrayList<>();
        List<WebElement> listOfBreadcrumbs = rootElement.findElements(By.tagName("li"));
        for (WebElement el : listOfBreadcrumbs) {
            list.add(el.getText());
        }
        return list;
    }
}
