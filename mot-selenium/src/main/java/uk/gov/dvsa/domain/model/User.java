package uk.gov.dvsa.domain.model;

import com.fasterxml.jackson.annotation.JsonAutoDetect;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

@JsonIgnoreProperties(ignoreUnknown = true)
@JsonAutoDetect(fieldVisibility = JsonAutoDetect.Visibility.ANY)
public class User {

    private String title;
    private String username;
    private String password;
    private String personId;
    private String firstName;
    private String middleName;
    private String surname;
    private String addressLine1;
    private String addressLine2;
    private String postcode;
    private String phoneNumber;
    private String emailAddress;
    private String dateOfBirth;
    private String drivingLicenceNumber;
    private String drivingLicenceRegion;
    private boolean multiSiteUser;
    private final String DEFAULT_TITLE = "Mr";

    public User() {
    }

    public User(String username, String password) {
        this.username = username;
        this.password = password;
    }

    public String getUsername() {
        return username;
    }

    public String getPassword() {
        return password;
    }

    public String getId() {
        return personId;
    }

    public String getFirstName() {
        return firstName;
    }

    public String getMiddleName() {
        return middleName;
    }

    public String getSurname() {
        return surname;
    }

    public String getAddressLine1() {
        return addressLine1;
    }

    public String getAddressLine2() {
        return addressLine2;
    }

    public String getPostcode() {
        return postcode;
    }

    public String getPhoneNumber() {
        return phoneNumber;
    }

    public String getEmailAddress() {
        return emailAddress;
    }

    public String getDateOfBirth() {
        return dateOfBirth;
    }

    public String getTitle() {
        if (title == null) {
            return DEFAULT_TITLE;
        }
        return title;
    }

    public String getDrivingLicenceNumber() {
        return drivingLicenceNumber;
    }

    public String getDrivingLicenceRegion() {
        return drivingLicenceRegion;
    }

    public boolean isManyVtsTester() {
        return multiSiteUser;
    }

    public String getFullName() {
        return getTitle() + " " + getFirstName() + (hasMiddleName() ? " " + getMiddleName() : "") + " "
                + getSurname();
    }

    public String getNamesAndSurname() {
        return getFirstName() + (hasMiddleName() ? " " + getMiddleName() : "") + " " + getSurname();
    }

    private boolean hasMiddleName() {
        return middleName != null && !middleName.isEmpty();
    }

    public String getPin() {
        return "123456";
    }

    public String getPersonId() {
        return personId;
    }

    @Override
    public String toString() {
        return "User{" +
                "title='" + title + '\'' +
                ", username='" + username + '\'' +
                ", password='" + password + '\'' +
                ", personId='" + personId + '\'' +
                ", firstName='" + firstName + '\'' +
                ", middleName='" + middleName + '\'' +
                ", surname='" + surname + '\'' +
                ", addressLine1='" + addressLine1 + '\'' +
                ", addressLine2='" + addressLine2 + '\'' +
                ", postcode='" + postcode + '\'' +
                ", phoneNumber='" + phoneNumber + '\'' +
                ", emailAddress='" + emailAddress + '\'' +
                ", dateOfBirth='" + dateOfBirth + '\'' +
                ", drivingLicenceNumber='" + drivingLicenceNumber + '\'' +
                '}';
    }
}

