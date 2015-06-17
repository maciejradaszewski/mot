package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.enums.PageTitles;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class SiteSearchResultsPage extends BasePage {

    //Page elements
    @FindBy(id = "navigation-link-") private WebElement backToSiteSearch;

    @FindBy(id = "dataTable") private WebElement ResultsTable;

    public SiteSearchResultsPage(WebDriver driver) {
        super(driver);
        waitForAjaxToComplete();
        checkTitle(PageTitles.SITE_SEARCH_RESULTS.getPageTitle());
    }

    public void searchAgain() {
        backToSiteSearch.click();
    }

    public SiteDetailsPage selectSiteLinkFromTable(String siteNumber) {
        driver.findElement(By.partialLinkText(siteNumber)).click();
        return new SiteDetailsPage(driver);
    }

    public SiteInformationSearchPage clickReturnToSiteSearchInformation(){
        backToSiteSearch.click();
        return new SiteInformationSearchPage(driver);
    }

    public boolean isTablePresent() {
        return ResultsTable.isDisplayed();
    }

    public SiteSearchResultsPage verifyFullTitle(String searchTerm){
        checkTitle(PageTitles.SITE_SEARCH_RESULTS.getPageTitle() + "\"" + searchTerm.toUpperCase() + "\"");
        return new SiteSearchResultsPage(driver);
    }

}
