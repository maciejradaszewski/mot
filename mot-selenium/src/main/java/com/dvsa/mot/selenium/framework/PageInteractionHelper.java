package com.dvsa.mot.selenium.framework;

import org.openqa.selenium.*;
import org.openqa.selenium.support.ui.ExpectedCondition;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.Wait;
import org.openqa.selenium.support.ui.WebDriverWait;

import java.util.Date;
import java.util.List;
import java.util.concurrent.TimeUnit;

public class PageInteractionHelper extends Configurator {


    protected static final int AJAX_MAXIMUM_TIMEOUT = 5;
    /*
        To contain helper methods for finding, interacting with and populating page elements
     */
    public WebDriver driver;

    public PageInteractionHelper(WebDriver driver) {
        this.driver = driver;
    }

    /**
     * Used for temporarily turning off implicit driver waits, should be followed by turning them back on after the
     * required step is done
     */
    protected void turnOffImplicitWaits() {
        driver.manage().timeouts().implicitlyWait(500, TimeUnit.MILLISECONDS);
    }

    /**
     * Used for resetting implicit waits to the default
     */
    protected void turnOnImplicitWaits() {
        driver.manage().timeouts().implicitlyWait(getDefaultDriverTimeout(), TimeUnit.SECONDS);
    }

    /**
     * Returns the element marked invalid by HTML5 validation
     */
    public WebElement findElementMarkedInvalid() {
        return driver.findElement(By.cssSelector("input:invalid"));
    }

    public List<WebElement> findElementWithoutImplicitWaits(By by) {
        turnOffImplicitWaits();
        List<WebElement> elements = driver.findElements(by);
        turnOnImplicitWaits();
        return elements;
    }

    protected WebElement waitForElementToBeVisible(By locator, int timeOut) {
        return new WebDriverWait(driver, timeOut)
                .until(ExpectedConditions.visibilityOfElementLocated(locator));
    }

    /**
     * Waits for the browser to indicate the page has completed loading
     */
    public void waitForPageToLoad() {
        ExpectedCondition<Boolean> pageLoad = new ExpectedCondition<Boolean>() {
            public Boolean apply(WebDriver driver) {
                return executeJavascript("return document.readyState").equals("complete");
            }
        };

        Wait<WebDriver> wait = new WebDriverWait(driver, 60);

        try {
            wait.until(pageLoad);
        } catch (Throwable pageLoadWaitError) {
            throw new TimeoutException("Timeout during page load");
        }
    }

    /**
     * Disables print popups.
     * This only works if the Javascript custom print method is configured to look for
     * an element with id printingDisabled. It is not a generic method to prevent printing!
     */
    protected void disablePrintingOnCurrentPage() {
        executeJavascript("newNode = document.createElement('span');"
                + "document.getElementsByTagName('html')[0].appendChild(newNode);"
                + "newNode.id='printingDisabled';");
    }

    /**
     * Wait for any jQuery activity to be completed. This is useful functionality when waiting for an AJAX call or
     * other JavaScript update process to complete.
     */
    protected void waitForAjaxToComplete() {
        ExpectedCondition<Boolean> jqueryActive = new ExpectedCondition<Boolean>() {
            public Boolean apply(WebDriver driver) {
                return executeJavascript("return (window.jQuery || { active : 0 }).active")
                        .toString().equals("0");
            }
        };

        Wait<WebDriver> wait = new WebDriverWait(driver, 60);

        try {
            wait.until(jqueryActive);
        } catch (Throwable pageLoadWaitError) {
            throw new TimeoutException("Timeout waiting for jQuery to complete");
        }
    }

    /**
     * Wait until the element specified is visible in the page
     *
     * @param element element to watch for
     * @param timeout maximum time to wait in seconds
     */
    protected void waitForElementToBeVisible(WebElement element, int timeout) {
        new WebDriverWait(driver, timeout).until(ExpectedConditions.visibilityOf(element));
    }


    protected boolean isElementClickable(WebElement element, int timeout) {
        return new WebDriverWait(driver, timeout)
                .until(ExpectedConditions.elementToBeClickable(element)) != null;
    }

    protected void waitForTextToBePresentInElement(WebElement element, String text, int timeout) {
        new WebDriverWait(driver, timeout)
                .until(ExpectedConditions.textToBePresentInElement(element, text));

    }

    public WebElement waitForElementToBeClickable(By locator) {
        return new WebDriverWait(driver, AJAX_MAXIMUM_TIMEOUT)
                .until(ExpectedConditions.elementToBeClickable(locator));
    }

    public WebElement waitForElementToBeClickable(WebElement element) {
        return new WebDriverWait(driver, AJAX_MAXIMUM_TIMEOUT)
                .until(ExpectedConditions.elementToBeClickable(element));
    }

    /**
     * Returns true if the element matched in the By is present in the page even if it is not displayed
     */
    protected boolean isElementPresent(By locator) {
        try {
            turnOffImplicitWaits();
            List<WebElement> elements = new WebDriverWait(driver, 1)
                    .until(ExpectedConditions.presenceOfAllElementsLocatedBy(locator));

            return elements.size() > 0;
        } catch (TimeoutException ex) {
            return false;
        } finally {
            turnOnImplicitWaits();
        }
    }

    /**
     * Returns true if the element is displayed in the page, no timeouts are used
     */
    protected boolean isElementDisplayed(WebElement element) {
        try {
            turnOffImplicitWaits();
            return element.isDisplayed();
        } catch (NoSuchElementException | TimeoutException ex) {
            return false;
        } finally {
            turnOnImplicitWaits();
        }
    }

    /**
     * Method for calling Javascript in the context of the page running in
     * Selenium
     *
     * @param script Javascript to be executed
     * @param args   Arguments for the script
     * @return Throws an exception if the script cannot be executed on current
     * driver, else see JavascriptExecutor
     * @see org.openqa.selenium.JavascriptExecutor
     */
    protected Object executeJavascript(String script, Object... args) {
        if (driver instanceof JavascriptExecutor) {
            return ((JavascriptExecutor) driver).executeScript(script, args);
        } else {
            throw new IllegalStateException(
                    "Cannot execute Javascript (the driver " + driver.getClass()
                            + " does not implement the JavascriptExecutor interface)");
        }
    }

    protected void takePageScreenshot(String screenShotPath) {
        String screenPhotoName =
                (this.getClass().getName().replace("com.dvsa.mot.selenium.", "")) + "_" +
                        screenshotDateFormat.format(new Date()) + ".png";

        Utilities.takeScreenShot(driver, screenPhotoName, screenShotPath);
    }
}
