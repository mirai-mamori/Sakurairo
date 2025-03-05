import { useEffect } from "react";

const useWindowResize = (handler) => {
	useEffect(() => {
		const listener = (e) => {
			handler();
		};

		window.addEventListener("resize", listener, true);

		return () => {
			window.removeEventListener("resize", listener, true);
		};
	}, [handler]);
};

export default useWindowResize;
