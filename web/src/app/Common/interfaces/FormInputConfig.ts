export interface FormInputConfig {
    type: string;
    name: string;
    placeholder?: string;
    label?: string;
    validation?: any[];
    icon?: string;
    smIcon?: string;
    widthClass?: string;
    hint?: string;
    defaultValue?: string;
    listValues?: any[];
    inputElementFocus?: boolean;
    isLoading?: boolean;
    dependsOn?: string;
    onChange?: string;
}
