package com.dvsa.mot.selenium.datasource;

import com.dvsa.mot.selenium.framework.RandomDataGenerator;

import java.util.UUID;

public class DemoTests {
    public enum DemoTestType {
        DEMO_TEST_AFTER_TRAINING(
                "Demonstration Test following training"), ROUTINE_DEMONSTRATION_TEST(
                "Routine Demonstration Test");

        private String demoTestType;

        private DemoTestType(String s) {
            demoTestType = s;
        }

        public String getDemoTestType() {
            return demoTestType;
        }
    }


    public enum DemoTestResult {
        SATISFACTORY("Satisfactory"), UNSATISFACTORY("Unsatisfactory");

        private String demoTestResult;

        private DemoTestResult(String s) {
            demoTestResult = s;
        }

        public String getDemoTestResult() {
            return demoTestResult;
        }
    }


    public enum TesterStatus {
        QUALIFIED("Qualified"), DEMONSTRATION_TEST_NEEDED(
                "Demonstration test needed"), REFRESHER_NEEDED(
                "Refresher needed"), INITIAL_TRAINING_NEEDED("Initial training needed");

        private String demoTestStatus;

        private TesterStatus(String s) {
            demoTestStatus = s;
        }

        public String getTesterStatus() {
            return demoTestStatus;
        }
    }


    public static final DemoTests Demo_Tests_Details_1 =
            new DemoTests("1234567890001", "5", "Bob Thomas Arctor",
                    DemoTestType.DEMO_TEST_AFTER_TRAINING.getDemoTestType(),
                    DemoTestResult.UNSATISFACTORY.getDemoTestResult(),
                    TesterStatus.QUALIFIED.getTesterStatus(), "",
                    RandomDataGenerator.generateRandomNumber(5, UUID.randomUUID().hashCode()));
    public static final DemoTests Demo_Tests_Details_2 =
            new DemoTests("123456789012", "32", "Demo Mot Test User",
                    DemoTestType.ROUTINE_DEMONSTRATION_TEST.getDemoTestType(),
                    DemoTestResult.SATISFACTORY.getDemoTestResult(),
                    TesterStatus.DEMONSTRATION_TEST_NEEDED.getTesterStatus(), "class2class4class7",
                    RandomDataGenerator.generateRandomNumber(5, UUID.randomUUID().hashCode()));
    public static final DemoTests Demo_Tests_Details_3 =
            new DemoTests("abc", "", "", "", "", "", "", "");
    public static final DemoTests Demo_Tests_Details_4 =
            new DemoTests("123456789212", "7", "Bob Thomas Arctor",
                    DemoTestType.ROUTINE_DEMONSTRATION_TEST.getDemoTestType(),
                    DemoTestResult.SATISFACTORY.getDemoTestResult(),
                    TesterStatus.REFRESHER_NEEDED.getTesterStatus(), "class2class4class7",
                    RandomDataGenerator.generateRandomNumber(5, UUID.randomUUID().hashCode()));
    public static final DemoTests Demo_Tests_Details_5 =
            new DemoTests("1234567891001", "1005", "Bob Thomas Arctor",
                    DemoTestType.ROUTINE_DEMONSTRATION_TEST.getDemoTestType(),
                    DemoTestResult.SATISFACTORY.getDemoTestResult(),
                    TesterStatus.INITIAL_TRAINING_NEEDED.getTesterStatus(), "class2class4class7",
                    RandomDataGenerator.generateRandomNumber(5, UUID.randomUUID().hashCode()));
    public static final DemoTests Demo_Tests_Details_6 =
            new DemoTests("1234567890001", "5", "Bob Thomas Arctor",
                    DemoTestType.DEMO_TEST_AFTER_TRAINING.getDemoTestType(),
                    DemoTestResult.SATISFACTORY.getDemoTestResult(),
                    TesterStatus.QUALIFIED.getTesterStatus(), "",
                    RandomDataGenerator.generateRandomNumber(5, UUID.randomUUID().hashCode()));
    public static final DemoTests Demo_Tests_Details_7 =
            new DemoTests("1234567890001", "5", "Bob Thomas Arctor",
                    DemoTestType.DEMO_TEST_AFTER_TRAINING.getDemoTestType(),
                    DemoTestResult.SATISFACTORY.getDemoTestResult(),
                    TesterStatus.REFRESHER_NEEDED.getTesterStatus(), "",
                    RandomDataGenerator.generateRandomNumber(5, UUID.randomUUID().hashCode()));
    public static final DemoTests Demo_Tests_Details_8 =
            new DemoTests("1234567890001", "5", "Bob Thomas Arctor",
                    DemoTestType.DEMO_TEST_AFTER_TRAINING.getDemoTestType(),
                    DemoTestResult.SATISFACTORY.getDemoTestResult(),
                    TesterStatus.DEMONSTRATION_TEST_NEEDED.getTesterStatus(), "",
                    RandomDataGenerator.generateRandomNumber(5, UUID.randomUUID().hashCode()));
    public static final DemoTests Demo_Tests_Details_9 =
            new DemoTests("1234567890001", "5", "Bob Thomas Arctor",
                    DemoTestType.DEMO_TEST_AFTER_TRAINING.getDemoTestType(),
                    DemoTestResult.SATISFACTORY.getDemoTestResult(),
                    TesterStatus.INITIAL_TRAINING_NEEDED.getTesterStatus(), "",
                    RandomDataGenerator.generateRandomNumber(5, UUID.randomUUID().hashCode()));
    public static final DemoTests Demo_Tests_Details_10 =
            new DemoTests("1234567891001", "1005", "Bob Thomas Arctor",
                    DemoTestType.ROUTINE_DEMONSTRATION_TEST.getDemoTestType(),
                    DemoTestResult.SATISFACTORY.getDemoTestResult(),
                    TesterStatus.QUALIFIED.getTesterStatus(), "class2class4class7",
                    RandomDataGenerator.generateRandomNumber(5, UUID.randomUUID().hashCode()));


    public final String userDetails;
    public final String testerId;
    public final String testerName;
    public final String testType;
    public final String testResult;
    public final String testerStatus;
    public final String classSelect;
    public final String Comments;

    public DemoTests(String userDetails, String testerId, String testerName, String testType,
            String testResult, String testerStatus, String classSelect, String Comments) {
        /**
         * sometimes test number - TODO check if it's allways MOT test number, and rename
         */
        this.userDetails = userDetails;
        this.testerId = testerId;
        this.testerName = testerName;
        this.testType = testType;
        this.testResult = testResult;
        this.testerStatus = testerStatus;
        this.classSelect = classSelect;
        this.Comments = Comments;
    }
}
