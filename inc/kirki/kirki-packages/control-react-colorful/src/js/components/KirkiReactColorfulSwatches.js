const KirkiReactColorfulSwatches = ({ colors, onClick }) => {
	return (
		<div className="kirki-color-swatches">
			{colors.map((clr, index) => {
				const color = clr && clr.color ? clr.color : clr;

				return (
					<button
						key={index.toString()}
						type="button"
						className="kirki-color-swatch"
						data-kirki-color={color}
						style={{ backgroundColor: color }}
						onClick={() => onClick(color)}
					></button>
				);
			})}
		</div>
	);
};

export default KirkiReactColorfulSwatches;
