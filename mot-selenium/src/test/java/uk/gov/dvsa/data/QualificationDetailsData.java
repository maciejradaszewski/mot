package uk.gov.dvsa.data;

import uk.gov.dvsa.domain.model.QualificationCertificate;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.service.QualificationDetailsService;
import uk.gov.dvsa.domain.service.UserService;

import java.io.IOException;

public class QualificationDetailsData extends QualificationDetailsService{

    public QualificationDetailsData() {}

    public QualificationCertificate createQualificationCertificateForGroupA(
        User user,
        String certificateNumber,
        String dateOfQualification,
        String siteNumber) throws IOException {

        return createQualificationCertificate(user, GROUP_A, certificateNumber, dateOfQualification,siteNumber);
    }

    public QualificationCertificate createQualificationCertificateForGroupB(
        User user,
        String certificateNumber,
        String dateOfQualification,
        String siteNumber) throws IOException {

        return createQualificationCertificate(user, GROUP_B, certificateNumber, dateOfQualification,siteNumber);
    }
}
