package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

import java.util.*;


public class ReasonForRejectionPage extends BasePage {

    @FindBy(tagName = "h1") private WebElement title;

    @FindBy(tagName = "a") private List<WebElement> links;

    @FindBy(className = "step") private WebElement stepInfo;

    @FindBy(id = "info-message") private WebElement infoMessage;

    @FindBy(linkText = "Manual") private WebElement manualButton;

    @FindBy(className = "modal-title") private WebElement rfrTitle;

    @FindBy(id = "reason-for-rejection-modal") private WebElement reasonsForRejection;

    @FindBy(id = "rfr-modal-close") private WebElement closeRFRList;

    @FindBy(id = "rfr-search") private WebElement searchForRFRByTyping;

    @FindBy(id = "test-item-selector-btn-search") private WebElement searchForRFR;

    @FindBy(id = "rfr-remove") private WebElement removeRFR;

    @FindBy(id = "rfr-submit-1055") private WebElement submitModalFailureLocation;

    @FindBy(id = "rfr-submit-7080") private WebElement submitModalLampFailureLocation;

    @FindBy(id = "mot-header-details-rfr-done") private WebElement doneButton;

    @FindBy(id = "manual-advisory") private WebElement manualAdvisory;

    @FindBy(id = "rfr-modal-close") private WebElement closeRFRModalBox;

    @FindBy(id = "dangerous") private WebElement failureIsDangerousCheckbox;

    @FindBy(name = "submit") private WebElement submitFail;

    @FindBy(linkText = "Cancel") private WebElement cancelFail;

    @FindBy(id = "fail-rfr-1055") private WebElement failFlagToCustomer;

    @FindBy(id = "prs-rfr-1055") private WebElement prsFlagToCustomer;

    @FindBy(id = "advisory-rfr-7089") private WebElement advisoryFlagToCustomer;

    @FindBy(id = "fail-rfr-7088") private WebElement clickRFRMissing;

    @FindBy(id = "mot-header-details-rfr-done") private WebElement DoneAddingRfrs;

    @FindBy(id = "breadcrumb") private WebElement breadcrumb;

    @FindBy(id = "advisory-rfr-8291") private WebElement rfrsClickAdvisoryButton;

    @FindBy(id = "prs-rfr-8394") private WebElement rfrsClickPRSButton;

    @FindBy(id = "rfrCount") private WebElement rfrCount;

    private Map<String, Integer> pageLinks = new HashMap<String, Integer>();

    public ReasonForRejectionPage(WebDriver driver) {
        super(driver);
    }

    /**
     * Get all RFR links from the page. For every link click on it and get all links from
     * returned page. If on the opened page there won't be any links then perform test.
     * Then go back to previous page and open next link.
     * In case of last link go back to previous page.
     */
    public Map<String, Integer> procesLinks() {
        String pageNumber = getPageNumber();
        if (!pageLinks.containsKey(pageNumber)) {
            pageLinks.put(pageNumber, 0);
        } else
            pageLinks.put(pageNumber, pageLinks.get(pageNumber) + 1);

        List<WebElement> allLinks = getAllLinks();
        if (allLinks.isEmpty()) {
            //System.out.println("**"+ driver.getCurrentUrl());
            //TODO probably screen capture
            driver.navigate().back();
            procesLinks();
        } else {
            if (pageLinks.get(pageNumber) < allLinks.size()) {
                clickLinkFromList(allLinks, pageLinks.get(pageNumber));
                procesLinks();
            } else {
                if (pageNumber.equals("test-item-selector"))
                    return pageLinks;
                driver.navigate().back();
                //TODO probably screen capture here for RFRs links
                procesLinks();
            }
        }
        return pageLinks;
    }

    public String getPageTitle() {
        return title.getText();
    }

    /**
     * Click on n-th link from provided list of WebElements
     *
     * @param Links
     * @param n
     */
    private void clickLinkFromList(List<WebElement> Links, Integer n) {
        WebElement tempLink = Links.get(n);
        tempLink.click();
    }

    /**
     * Return last part of the current url
     *
     * @return
     */
    public String getPageNumber() {
        String url = driver.getCurrentUrl();
        return url.substring(url.lastIndexOf('/') + 1, url.length());
    }

    /**
     * Get list of all available links excluding Search button,
     * Fail button,PRS button, Advisory button,Details
     *
     * @return List of Link elements
     */
    public List<WebElement> getAllLinks() {
        List<WebElement> link = links;
        List<WebElement> cleanedLink = new ArrayList<WebElement>();
        Iterator<WebElement> iterator = link.iterator();
        while (iterator.hasNext()) {
            WebElement curElem = iterator.next();
            String strElem = curElem.getText();
            if (strElem.equals("Search") ||
                    strElem.equals("Fail") || strElem.equals("PRS") || strElem.equals("Advisory")
                    || strElem.equals("Details") || strElem.equals("")) {
                iterator.remove();
                continue;
            }
            cleanedLink.add(curElem);
        }
        return cleanedLink;
    }

    /**
     * Search for a RFR by typing into the text box rather than going through the categories
     */
    public ReasonForRejectionPage searchForRFRTextBox(String text) {
        searchForRFRByTyping.clear();
        searchForRFRByTyping.sendKeys(text);
        waitForAjaxToComplete();
        return new ReasonForRejectionPage(driver);
    }

    public ReasonForRejectionPage searchForRfr(String text) {
        searchForRFRByTyping.clear();
        searchForRFRByTyping.sendKeys(text);
        searchForRFRByTyping.submit();

        return this;
    }

    /**
     * To complete the search, click Search icon
     */
    public ReasonForRejectionPage searchForRFRClickSearchIcon() {
        searchForRFR.click();
        waitForAjaxToComplete();
        return new ReasonForRejectionPage(driver);
    }

    /**
     * Click Done when an RFR has been added successfully
     */
    public MotTestPage clickDone() {
        waitForElementToBeVisible(doneButton, defaultWebElementTimeout);
        doneButton.click();
        return new MotTestPage(driver);
    }

    public MotTestPage clickDone(String title) {
        waitForElementToBeVisible(doneButton, defaultWebElementTimeout);
        doneButton.click();
        return new MotTestPage(driver, title);
    }

    public MOTRetestPage clickDoneExpectingMotRetestPage() {
        waitForElementToBeVisible(doneButton, defaultWebElementTimeout);
        doneButton.click();
        return new MOTRetestPage(driver);
    }

    public ManualAdvisoryPage addManualyAdvisor() {
        manualAdvisory.click();
        return new ManualAdvisoryPage(driver);
    }

    public FailureLocationPage clickFailureButton(String id) {
        waitForElementToBeVisible(By.id("fail-rfr-" + id), defaultWebElementTimeout);
        WebElement failureButton = driver.findElement(By.id("fail-rfr-" + id));
        failureButton.click();
        waitForElementToBeVisible(driver.findElement(By.id("modal-rfr-title-" + id)),
                defaultWebElementTimeout);
        return new FailureLocationPage(driver);
    }

    public FailureLocationPage clickPRSButton(String id) {
        waitForElementToBeVisible(By.id("prs-rfr-" + id), defaultWebElementTimeout);
        WebElement failureButton = driver.findElement(By.id("prs-rfr-" + id));
        failureButton.click();
        return new FailureLocationPage(driver);
    }

    public FailureLocationPage clickAdvisoryButton(String id) {
        waitForElementToBeVisible(By.id("advisory-rfr-" + id), defaultWebElementTimeout);
        WebElement failureButton = driver.findElement(By.id("advisory-rfr-" + id));
        failureButton.click();
        return new FailureLocationPage(driver);
    }

    public ReasonForRejectionPage addFailure(FailureRejection failure) {
        int previousCount = getPreviousRfrCount();
        searchForRFRTextBox(Integer.toString(failure.reason.reasonId)).searchForRFRClickSearchIcon()
                .clickFailureButton(Integer.toString(failure.reason.reasonId))
                .checkRfrText(failure.reason, false)
                .enterFailureLocation(failure.reason.reasonId, failure.failureLocation)
                .addFailureLocation(failure.reason.reasonId);
        waitForElementToBeVisible(title, defaultWebElementTimeout);
        waitForRfrUpdate(previousCount);
        return new ReasonForRejectionPage(driver);
    }

    public ReasonForRejectionPage addPRS(PRSrejection prs) {
        int previousCount = getPreviousRfrCount();
        searchForRFRTextBox(Integer.toString(prs.reason.reasonId));
        String searchText = Integer.toString(prs.reason.reasonId);
        searchForRFRTextBox(searchText).searchForRFRClickSearchIcon()
                .clickPRSButton(Integer.toString(prs.reason.reasonId))
                .checkRfrText(prs.reason, false)
                .enterFailureLocation(prs.reason.reasonId, FailureLocation.failureLocation_CASE1)
                .addFailureLocation(prs.reason.reasonId);
        waitForElementToBeVisible(title, defaultWebElementTimeout);
        waitForRfrUpdate(previousCount);
        return new ReasonForRejectionPage(driver);
    }


    public ReasonForRejectionPage selectCategory(VehicleCategory category) {
        WebElement e = driver.findElement(By.partialLinkText(category.categoryDescription));
        e.click();
        return new ReasonForRejectionPage(driver);
    }

    public ReasonForRejectionPage addAdvisory(AdvisoryRejection advisory) {
        int previousCount = getPreviousRfrCount();
        searchForRFRTextBox(Integer.toString(advisory.reason.reasonId))
                .searchForRFRClickSearchIcon()
                .clickAdvisoryButton(Integer.toString(advisory.reason.reasonId))
                .checkRfrText(advisory.reason, true)
                .enterFailureLocation(advisory.reason.reasonId, advisory.failureLocation)
                .addFailureLocation(advisory.reason.reasonId);
        waitForElementToBeVisible(title, defaultWebElementTimeout);
        waitForRfrUpdate(previousCount);
        return new ReasonForRejectionPage(driver);
    }

    public String getBreadcrumbText() {
        return breadcrumb.getText();
    }

    public boolean isBreadcrumbVisible() {
        List<WebElement> breadcrumbSearch = driver.findElements(By.id("breadcrumb"));
        return breadcrumbSearch.size() == 1 ? breadcrumbSearch.get(0).isDisplayed() : false;
    }

    public ReasonForRejectionPage selectCategoryFromBreadcrumb(VehicleCategory category) {
        WebElement e = driver.findElement(By.partialLinkText(category.categoryDescription));
        e.click();
        return new ReasonForRejectionPage(driver);
    }

    public int getRfrCount() {
        return Integer.parseInt(rfrCount.getText().trim().split(" ")[0]);
    }

    public int getPreviousRfrCount() {
        turnOffImplicitWaits();
        if (driver.findElements(By.id("rfrCount")).size() > 0) {
            turnOnImplicitWaits();
            return getRfrCount();
        } else {
            turnOnImplicitWaits();
            return 0;
        }
    }

    public ReasonForRejectionPage waitForRfrUpdate(int previousCount) {
        waitForElementToBeVisible(rfrCount, defaultWebElementTimeout);
        WebDriverWait wait = new WebDriverWait(driver, defaultWebElementTimeout);
        wait.until(ExpectedConditions
                .textToBePresentInElement(rfrCount, (previousCount + 1) + " Listed"));
        return new ReasonForRejectionPage(driver);
    }

    public boolean isErrorMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }
}
