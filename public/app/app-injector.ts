let appInjectorRef;

export const appInjector = (injector = null) => {

    if (!injector) {
        console.log('Returning injector', appInjectorRef);
        return appInjectorRef;
    }
    console.log('Setting injector', injector);
    appInjectorRef = injector;
    return appInjectorRef;
};