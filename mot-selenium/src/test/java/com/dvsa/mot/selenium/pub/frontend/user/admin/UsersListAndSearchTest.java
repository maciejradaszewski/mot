package com.dvsa.mot.selenium.pub.frontend.user.admin;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.pub.frontend.user.admin.pages.UsersListPage;
import org.testng.Assert;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;
import static org.hamcrest.number.OrderingComparison.greaterThan;

public class UsersListAndSearchTest extends BaseTest {

    @DataProvider(name = "DP-UserListCount") public Object[][] userListCount() {
        return new Object[][] {{10}, {25}, {50}, {100},};
    }

    @Test(groups = {"VM-2120", "VM-2121"}) public void testUsersListShouldBeRecordsPresent() {
        UsersListPage systemUsers =
                UsersListPage.navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1);
        assertThat("Assert number of users in list is > 0.", systemUsers.getNumberOfUsersInList(),
                greaterThan(0));
    }

    @Test(groups = {"VM-2120", "VM-2121"}, dataProvider = "DP-UserListCount")
    public void testUserSearchByUserId(int noOfUsers) {
        UsersListPage systemUsers =
                UsersListPage.navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1);
        assertThat("System users label is displayed", systemUsers.isLabelSystemUsersDisplayed(),
                is(true));
        switch (noOfUsers) {
            case 10:
                systemUsers.select10UserPerPage();
                break;
            case 25:
                systemUsers.select25UserPerPage();
                break;
            case 50:
                systemUsers.select50UserPerPage();
                break;
            case 100:
                systemUsers.select100UserPerPage();
                break;
            default:
                Assert.fail("Unknown value");
        }
        systemUsers.searchCriteria("10");
        assertThat("Assert User ID is displayed.", systemUsers.isUserIdPresent("10"), is(true));
    }

    @Test(groups = {"VM-2120", "VM-2121"}) public void testUserSearchByUserName() {
        UsersListPage systemUsers =
                UsersListPage.navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1);
        systemUsers.select50UserPerPage();
        systemUsers.searchCriteria(Login.LOGIN_MANYVTSTESTER_NOVTSTESTER.username);
        assertThat("Assert username is displayed.",
                systemUsers.isUserNamePresent(Login.LOGIN_MANYVTSTESTER_NOVTSTESTER.username),
                is(true));
    }

    @Test(groups = {"VM-2120", "VM-2121"}) public void testUserSearchByName() {
        UsersListPage systemUsers =
                UsersListPage.navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1);
        Assert.assertEquals(true, systemUsers.isLabelSystemUsersDisplayed());
        systemUsers.select100UserPerPage();
        systemUsers.searchCriteria(Person.MALLORY_ARCHER.getFullName());
        assertThat("Particular Name is found in the list",
                systemUsers.isNamePresent(Person.MALLORY_ARCHER.getFullName()), is(true));
    }
}
