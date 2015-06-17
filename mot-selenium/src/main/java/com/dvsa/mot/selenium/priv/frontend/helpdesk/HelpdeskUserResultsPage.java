package com.dvsa.mot.selenium.priv.frontend.helpdesk;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import java.util.List;

public class HelpdeskUserResultsPage extends BasePage {
    private String PAGE_TITLE = "SEARCH RESULTS";

    @FindBy(id = "return_to_user_search")
    private WebElement backToUserSearch;
    
    public HelpdeskUserResultsPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }
    
    public HelpdeskUserSearchPage backToUserSearch() {
        backToUserSearch.click();
        return new HelpdeskUserSearchPage(driver);
    }
    
    private List<WebElement> getResults() {
        turnOffImplicitWaits();
        List<WebElement> results = driver.findElements(By.xpath("//*[@data-element='result-details']"));
        turnOnImplicitWaits();
        return results;
    }
    
    public int getNumResults() {
        return getResults().size();
    }
    
    private WebElement getResultByPosition(int position) {
        return getResults().get(position);
    }
    
    public String getResultName(int resultPosition) {
        WebElement result = getResultByPosition(resultPosition);
        return result.findElement(By.cssSelector("[data-element='result-username']")).getText();
    }

    public HelpDeskUserProfilePage clickUserName(int resultPosition){
        WebElement result = getResults().get(resultPosition);
        result.findElement(By.cssSelector("[data-element='result-username']")).findElement(By.tagName("a")).click();
        return new HelpDeskUserProfilePage(driver);
    }
    
    public String getResultDateOfBirth(int resultPosition) {
        WebElement result = getResultByPosition(resultPosition);
        return result.findElement(By.cssSelector("[data-element='result-user-dob']")).getText();
    }
    
    public String getResultAddress(int resultPosition) {
        WebElement result = getResultByPosition(resultPosition);
        return result.findElement(By.cssSelector("[data-element='result-user-address']")).getText();
    }
    
    public String getResultPostcode(int resultPosition) {
        WebElement result = getResultByPosition(resultPosition);
        return result.findElement(By.cssSelector("[data-element='result-user-postcode']")).getText();
    }

    public boolean checkSearchResultsTable(String name) {
        turnOffImplicitWaits();
        List<WebElement> searchResultsTable = driver.findElements(
                By.xpath("//table[@id='results']//th"));
        turnOnImplicitWaits();
        for ( WebElement webElement : searchResultsTable ) {
            if ( webElement.getText().contains(name)) {
                return true;
            }
        }
        return false;
    }

}
